<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientOrderSubmission;
use App\Models\FinalMetadata;
use App\Models\FileManager;
use App\Traits\ResponseTrait;
use App\Services\BrevoService;
use Barryvdh\DomPDF\Facade\Pdf;
use ArPHP\I18N\Arabic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FinalMetadataController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $query = ClientOrderSubmission::with([
                'journal',
                'issue',
                'client_order.client',
                'authors',
                'finalMetadata'
            ])
            ->whereNotNull('acceptance_date')
            ->whereIn('approval_status', [
                SUBMISSION_ORDER_STATUS_ACCEPTED,
                SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION,
                SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED,
                SUBMISSION_ORDER_STATUS_PUBLISHED,
            ])
            ->whereHas('client_order', function ($q) {
                $q->where('payment_status', PAYMENT_STATUS_PAID);
            });

            $tab = $request->get('tab', 'pending');
            if ($tab === 'issued') {
                $query->whereNotNull('acceptance_certificate_file_id');
            } else {
                $tab = 'pending';
                $query->whereNull('acceptance_certificate_file_id');
            }

            // Filter by journal if provided
            if ($request->journal_id) {
                $query->where('journal_id', $request->journal_id);
            }

            // Filter by date range if provided
            if ($request->date_from) {
                $query->whereDate('acceptance_date', '>=', $request->date_from);
            }
            if ($request->date_to) {
                $query->whereDate('acceptance_date', '<=', $request->date_to);
            }

            // Search by title, author, or submission ID
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('article_title', 'like', '%' . $request->search . '%')
                      ->orWhere('id', 'like', '%' . $request->search . '%')
                      ->orWhereHas('authors', function($authorQuery) use ($request) {
                          $authorQuery->where('first_name', 'like', '%' . $request->search . '%')
                                     ->orWhere('last_name', 'like', '%' . $request->search . '%')
                                     ->orWhere('email', 'like', '%' . $request->search . '%');
                      });
                });
            }

            $data['pageTitle'] = __('Final Acceptance Certificates');
            $data['submissions'] = $query->orderBy('acceptance_date', 'desc')->paginate(20);
            $data['journals'] = \App\Models\Journal::where('status', 'active')->get();
            $data['filters'] = $request->only(['journal_id', 'date_from', 'date_to', 'search', 'tab']);
            $data['tab'] = $tab;

            // Counts for tabs (same base constraints, only varying certificate existence)
            $baseForCounts = ClientOrderSubmission::query()
                ->whereNotNull('acceptance_date')
                ->whereIn('approval_status', [
                    SUBMISSION_ORDER_STATUS_ACCEPTED,
                    SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION,
                    SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED,
                    SUBMISSION_ORDER_STATUS_PUBLISHED,
                ])
                ->whereHas('client_order', function ($q) {
                    $q->where('payment_status', PAYMENT_STATUS_PAID);
                });
            $data['pendingCount'] = (clone $baseForCounts)->whereNull('acceptance_certificate_file_id')->count();
            $data['issuedCount'] = (clone $baseForCounts)->whereNotNull('acceptance_certificate_file_id')->count();
            $data['activeOrder'] = 'active';
            $data['activeFinalAcceptanceCertificates'] = 'active';

            return view('admin.submissions.final-acceptance-certificates', $data);
        } catch (\Exception $e) {
            Log::error('Final acceptance certificates list error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred. Please try again.'));
        }
    }

    public function review(Request $request, $submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with([
                'authors',
                'journal',
                'client_order.client',
                'finalMetadata'
            ])->findOrFail($submissionId);

            $finalMetadata = $submission->finalMetadata ?? null;

            $data['pageTitle'] = __('Review Final Metadata');
            $data['submission'] = $submission;
            $data['finalMetadata'] = $finalMetadata;
            $data['activeOrder'] = 'active';

            return view('admin.submissions.final-metadata-review', $data);
        } catch (\Exception $e) {
            Log::error('Final metadata review error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred. Please try again.'));
        }
    }

    public function getJournalIssues($journal_id)
    {
        try {
            $issues = \App\Models\Issue::with(['submissions'])
                ->where('journal_id', $journal_id)
                ->whereIn('status', ['scheduled', 'published'])
                ->orderByDesc('year')
                ->orderByDesc('volume')
                ->orderByDesc('number')
                ->get()
                ->map(function ($issue) {
                    return [
                        'id' => $issue->id,
                        'volume' => $issue->volume,
                        'number' => $issue->number,
                        'year' => $issue->year,
                        'title' => $issue->title,
                        'status' => $issue->status,
                        'publication_date' => $issue->publication_date ? $issue->publication_date->format('Y-m-d') : ($issue->planned_publication_date ? $issue->planned_publication_date->format('Y-m-d') : null),
                        'articles_count' => $issue->submissions()->count(),
                    ];
                });

            return $this->success(['issues' => $issues]);
        } catch (\Exception $e) {
            Log::error('Get journal issues error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while fetching issues.'));
        }
    }

    public function generateCertificate(Request $request, $submission_id)
    {
        try {
            DB::beginTransaction();

            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with(['authors', 'journal', 'finalMetadata', 'client_order.client', 'issue'])->findOrFail($submissionId);

            $allowedStatuses = [
                SUBMISSION_ORDER_STATUS_ACCEPTED,
                SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION,
                SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED,
                SUBMISSION_ORDER_STATUS_PUBLISHED,
            ];
            if (!in_array($submission->approval_status, $allowedStatuses, true)) {
                return $this->error([], __('This manuscript is not eligible for a final acceptance certificate.'));
            }

            if (!$submission->client_order || $submission->client_order->payment_status !== PAYMENT_STATUS_PAID) {
                return $this->error([], __('Payment is not completed.'));
            }

            // Validate issue selection (required)
            $request->validate([
                'issue_id' => 'required|exists:issues,id',
            ]);

            // Check if certificate already exists
            if ($submission->acceptance_certificate_file_id) {
                return $this->error([], __('Certificate has already been generated.'));
            }

            // Assign submission to the selected issue
            $issue = \App\Models\Issue::findOrFail($request->issue_id);
            $submission->issue_id = $issue->id;

            // Generate and save final acceptance certificate
            // Use final metadata if available, otherwise use submission data
            $certificateFileId = $this->generateFinalAcceptanceCertificate($submission);
            if ($certificateFileId) {
                $submission->acceptance_certificate_file_id = $certificateFileId;
                $submission->save();

                // Track issued certificate history (best-effort)
                try {
                    \App\Models\CertificateHistory::create([
                        'client_order_submission_id' => $submission->id,
                        'file_id' => $certificateFileId,
                        'journal_name' => $submission->journal?->title,
                        'volume' => $issue->volume,
                        'issue' => $issue->number,
                        'acceptance_date' => optional($submission->acceptance_date)->toDateString(),
                        'publication_date' => $submission->publication_date ?? $issue->publication_date,
                        'editor_in_chief' => $submission->journal?->editor_in_chief,
                        'is_active' => true,
                        'issued_by' => auth()->id(),
                        'issued_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Could not write certificate history: ' . $e->getMessage());
                }

                DB::commit();
                return $this->success([], __('Final acceptance certificate generated successfully.'));
            } else {
                DB::rollBack();
                return $this->error([], __('Failed to generate certificate. Please try again.'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Certificate generation error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while generating the certificate.'));
        }
    }

    public function reviewAction(Request $request)
    {
        try {
            DB::beginTransaction();

            $submissionId = decrypt($request->submission_id);
            $submission = ClientOrderSubmission::findOrFail($submissionId);
            $action = $request->action;

            if ($submission->metadata_status !== 'pending_editor_review') {
                return redirect()->back()->with('error', __('This metadata is not pending review.'));
            }

            if ($action === 'approve') {
                $submission->metadata_status = 'approved';

                $canIssueCertificate = $submission->client_order && $submission->client_order->payment_status === PAYMENT_STATUS_PAID;
                if ($canIssueCertificate && !$submission->acceptance_certificate_file_id) {
                    $certificateFileId = $this->generateFinalAcceptanceCertificate($submission);
                    if ($certificateFileId) {
                        $submission->acceptance_certificate_file_id = $certificateFileId;
                        // Track issued certificate history (best-effort)
                        try {
                            \App\Models\CertificateHistory::create([
                                'client_order_submission_id' => $submission->id,
                                'file_id' => $certificateFileId,
                                'journal_name' => $submission->journal?->title,
                                'acceptance_date' => optional($submission->acceptance_date)->toDateString(),
                                'publication_date' => $submission->publication_date,
                                'is_active' => true,
                                'issued_by' => auth()->id(),
                                'issued_at' => now(),
                            ]);
                        } catch (\Exception $e) {
                            Log::warning('Could not write certificate history: ' . $e->getMessage());
                        }
                    }
                }

                // Proofreading stage should already be set after acceptance
                // But ensure it's set here as well
                if (!$submission->workflow_stage) {
                    $submission->workflow_stage = 'proofreading';
                }

                $submission->save();

                // Send notification email to author with certificate
                $brevoService = new BrevoService();
                $authorEmails = [];

                if ($submission->has_author && $submission->authors) {
                    foreach ($submission->authors as $author) {
                        if (!empty($author->email)) {
                            $authorEmails[] = $author->email;
                        }
                    }
                }

                if (empty($authorEmails) && $submission->client_order && $submission->client_order->client) {
                    $authorEmails[] = $submission->client_order->client->email;
                }

                if (!empty($authorEmails)) {
                    $emailSubject = $submission->acceptance_certificate_file_id
                        ? __('Final Metadata Approved - Certificate Ready')
                        : __('Final Metadata Approved');
                    $emailBody = $this->buildApprovalEmail($submission);

                    $brevoService->sendEmail(
                        implode(',', $authorEmails),
                        null,
                        $emailSubject,
                        $emailBody
                    );

                    if ($submission->acceptance_certificate_file_id) {
                        $submission->certificate_sent_at = now();
                        $submission->save();
                    }
                }

                DB::commit();
                return redirect()->route('admin.submissions.final-acceptance-certificates.index')
                    ->with('success', $submission->acceptance_certificate_file_id
                        ? __('Metadata approved successfully. Certificate generated.')
                        : __('Metadata approved successfully. Certificate will be available after payment.'));
            } elseif ($action === 'request_corrections') {
                $request->validate([
                    'corrections_note' => 'required|string|min:10',
                ]);

                $submission->metadata_status = 'pending_author';
                $submission->save();

                // Send notification email to author with corrections
                $brevoService = new BrevoService();
                $authorEmails = [];

                if ($submission->has_author && $submission->authors) {
                    foreach ($submission->authors as $author) {
                        if (!empty($author->email)) {
                            $authorEmails[] = $author->email;
                        }
                    }
                }

                if (empty($authorEmails) && $submission->client_order && $submission->client_order->client) {
                    $authorEmails[] = $submission->client_order->client->email;
                }

                if (!empty($authorEmails)) {
                    $emailSubject = __('Corrections Requested for Final Metadata');
                    $emailBody = $this->buildCorrectionsEmail($submission, $request->corrections_note);

                    $brevoService->sendEmail(
                        implode(',', $authorEmails),
                        null,
                        $emailSubject,
                        $emailBody
                    );
                }

                DB::commit();
                return redirect()->route('admin.submissions.final-acceptance-certificates.index')
                    ->with('success', __('Corrections requested. Author has been notified.'));
            } else {
                return redirect()->back()->with('error', __('Invalid action.'));
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Final metadata review action error: ' . $e->getMessage());
            return $this->error([], __('An error occurred. Please try again.'));
        }
    }

    private function buildApprovalEmail($submission)
    {
        $certificateUrl = route('admin.submissions.final-acceptance-certificate.download', encrypt($submission->id));

        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $body .= '<h2 style="color: #28a745;">' . __('Final Metadata Approved') . '</h2>';
        $body .= '<p>' . __('Congratulations! Your final metadata has been approved by the editor and your acceptance certificate is ready.') . '</p>';
        $body .= '<p><strong>' . __('Article Title') . ':</strong> ' . e($submission->article_title ?? __('N/A')) . '</p>';
        $body .= '<p><strong>' . __('Journal') . ':</strong> ' . e($submission->journal->title ?? __('N/A')) . '</p>';
        if ($submission->acceptance_certificate_file_id) {
            $body .= '<p><a href="' . $certificateUrl . '" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 15px;">' . __('Download Certificate') . '</a></p>';
        }
        $body .= '<p>' . __('The next steps in the publication process will be communicated to you shortly.') . '</p>';
        $body .= '<hr><p style="color: #666; font-size: 12px;">' . __('This is an automated message from the editorial team.') . '</p>';
        $body .= '</body></html>';
        return $body;
    }

    private function buildCorrectionsEmail($submission, $correctionsNote)
    {
        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $body .= '<h2 style="color: #ffc107;">' . __('Corrections Requested') . '</h2>';
        $body .= '<p>' . __('The editor has requested corrections to your final metadata. Please review the following:') . '</p>';
        $body .= '<p><strong>' . __('Article Title') . ':</strong> ' . e($submission->article_title ?? __('N/A')) . '</p>';
        $body .= '<h3>' . __('Requested Corrections') . '</h3>';
        $body .= '<div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0;">';
        $body .= '<p>' . nl2br(e($correctionsNote)) . '</p>';
        $body .= '</div>';
        $body .= '<p><a href="' . route('user.submission.final-metadata.form', encrypt($submission->id)) . '" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;">' . __('Update Final Metadata') . '</a></p>';
        $body .= '<hr><p style="color: #666; font-size: 12px;">' . __('This is an automated message from the editorial team.') . '</p>';
        $body .= '</body></html>';
        return $body;
    }

    private function generateFinalAcceptanceCertificate($submission)
    {
        try {
            $submission->load(['authors', 'journal', 'finalMetadata', 'client_order.client']);
            $finalMetadata = $submission->finalMetadata;

            // If final metadata doesn't exist, we can still generate certificate using submission data
            // This allows certificate generation right after acceptance

            // Prepare certificate data
            $Arabic = new Arabic();
            $language = selectedLanguage();
            $isLanguageArabic = false;
            if ($language->iso_code == 'ar') {
                $isLanguageArabic = true;
            }

            // Build authors list + clean human-readable affiliations
            $authors = [];
            $affiliations = [];

            if ($submission->authors && $submission->authors->count() > 0) {
                foreach ($submission->authors as $author) {
                    $authors[] = trim($author->first_name . ' ' . $author->last_name);

                    $affiliationData = $author->affiliation ?? null;
                    if (!$affiliationData) {
                        continue;
                    }

                    // Normalize to array if JSON string
                    if (is_string($affiliationData)) {
                        $decoded = json_decode($affiliationData, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $affiliationData = $decoded;
                        }
                    }

                    $authorAffiliations = [];

                    if (is_array($affiliationData)) {
                        // Ensure we are working with a list
                        $list = array_values($affiliationData);

                        foreach ($list as $item) {
                            if (is_array($item)) {
                                $parts = [];
                                foreach (['university', 'faculty', 'department', 'city', 'country'] as $key) {
                                    if (!empty($item[$key]) && trim($item[$key])) {
                                        $parts[] = trim($item[$key]);
                                    }
                                }
                                if (!empty($parts)) {
                                    $authorAffiliations[] = implode(', ', $parts);
                                }
                            } elseif (is_string($item) && trim($item) !== '') {
                                $authorAffiliations[] = trim($item);
                            }
                        }
                    } elseif (is_string($affiliationData) && trim($affiliationData) !== '') {
                        // Fallback: strip brackets/quotes from raw JSON-like strings
                        $clean = trim($affiliationData);
                        $clean = trim($clean, "[]\"");
                        $authorAffiliations[] = $clean;
                    }

                    if (!empty($authorAffiliations)) {
                        $affiliation = implode(' | ', $authorAffiliations);
                        if (!in_array($affiliation, $affiliations)) {
                            $affiliations[] = $affiliation;
                        }
                    }
                }
            }

            $authorsString = implode(', ', $authors);
            $affiliationsString = implode(' | ', $affiliations);

            // Handle acceptance_date - convert to Carbon if it's a string
            $acceptanceDate = $submission->acceptance_date;
            if ($acceptanceDate) {
                if (is_string($acceptanceDate)) {
                    $acceptanceDate = \Carbon\Carbon::parse($acceptanceDate);
                }
                $acceptanceDateFormatted = $acceptanceDate->format('F j, Y');
            } else {
                $acceptanceDateFormatted = now()->format('F j, Y');
            }

            $data = [
                'authors' => $authorsString,
                'affiliations' => $affiliationsString,
                'paper_title' => $finalMetadata->final_title ?? $submission->article_title,
                'journal_name' => $submission->journal->title ?? __('N/A'),
                'acceptance_date' => $acceptanceDateFormatted,
                'signature' => 'Dr. Jane Smith', // Can be made configurable
            ];

            // Arabic support
            foreach ($data as $key => $value) {
                if (preg_match('/[\x{0600}-\x{06FF}]/u', $value)) {
                    $data[$key] = $Arabic->utf8Glyphs($value);
                }
            }

            if ($isLanguageArabic) {
                $staticData = [
                    'headerOneStatic' => 'شهادة القبول النهائي',
                    'paraOneStatic' => 'هذه الشهادة تمنح إلى',
                    'paraTwoStatic' => 'من',
                    'paraThreeStatic' => 'لنشر البحث العلمي المعنون',
                    'journalInfoParaOneStatic' => 'نشر في',
                    'acceptanceDateStatic' => 'تاريخ القبول',
                    'signatureParaStatic' => 'رئيس هيئة التحرير',
                ];
                $data = array_merge($data, $staticData);
            }

            // Generate PDF
            $pdf = Pdf::loadView('admin.certificate.final-acceptance.details', $data);
            $pdf->setPaper('A4', 'portrait');

            if ($isLanguageArabic) {
                $pdf->setOption('defaultFont', 'arabicfont');
            }
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('isFontSubsettingEnabled', true);

            // Save PDF to storage
            $fileName = 'final-acceptance-certificate-' . $submission->id . '-' . time() . '.pdf';
            $filePath = 'uploads/Certificate/' . $fileName;

            Storage::disk(config('app.STORAGE_DRIVER'))->put($filePath, $pdf->output());

            // Create FileManager record
            $fileManager = new FileManager();
            $fileManager->file_type = 'application/pdf';
            $fileManager->file_role = 'certificate'; // Task 28: Set file role
            $fileManager->storage_type = config('filesystems.default');
            $fileManager->original_name = 'Final Acceptance Certificate.pdf';
            $fileManager->file_name = $fileName;
            $fileManager->user_id = auth()->id();
            $fileManager->path = $filePath;
            $fileManager->extension = 'pdf';
            $fileManager->size = Storage::disk(config('app.STORAGE_DRIVER'))->size($filePath);
            $fileManager->save();

            // Copy to public if using public storage
            if (config('app.STORAGE_DRIVER') == 'public' && !env('IS_SYMLINK_SUPPORT', true)) {
                $publicPath = public_path('storage/' . $filePath);
                $storagePath = storage_path('app/public/' . $filePath);
                if (file_exists($storagePath)) {
                    $dir = dirname($publicPath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    copy($storagePath, $publicPath);
                }
            }

            return $fileManager->id;
        } catch (\Exception $e) {
            Log::error('Certificate generation error: ' . $e->getMessage());
            return null;
        }
    }

    public function downloadCertificate(Request $request, $submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::findOrFail($submissionId);

            if (!$submission->acceptance_certificate_file_id) {
                return redirect()->back()->with('error', __('Certificate not found.'));
            }

            $fileManager = FileManager::find($submission->acceptance_certificate_file_id);
            if (!$fileManager) {
                return redirect()->back()->with('error', __('Certificate file not found.'));
            }

            $filePath = $fileManager->path;
            $storageDriver = config('app.STORAGE_DRIVER');

            if ($storageDriver === 'public') {
                $fullPath = storage_path('app/public/' . $filePath);
            } else {
                // For non-public drivers, stream the file
                $fileContent = Storage::disk($storageDriver)->get($filePath);
                return response($fileContent, 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $fileManager->original_name . '"');
            }

            if (!file_exists($fullPath)) {
                return redirect()->back()->with('error', __('Certificate file does not exist.'));
            }

            return response()->download($fullPath, $fileManager->original_name);
        } catch (\Exception $e) {
            Log::error('Certificate download error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred while downloading the certificate.'));
        }
    }

    public function editCertificate($submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with([
                'authors',
                'journal',
                'issue',
                'finalMetadata',
                'client_order.client'
            ])->findOrFail($submissionId);

            if (!$submission->acceptance_certificate_file_id) {
                return redirect()->back()->with('error', __('Certificate not generated yet.'));
            }

            $data['pageTitle'] = __('Edit Certificate');
            $data['submission'] = $submission;
            $data['finalMetadata'] = $submission->finalMetadata;
            $data['activeOrder'] = 'active';

            return view('admin.submissions.edit-certificate', $data);
        } catch (\Exception $e) {
            Log::error('Edit certificate error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred.'));
        }
    }

    public function updateCertificate(Request $request, $submission_id)
    {
        try {
            DB::beginTransaction();

            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with(['authors', 'journal', 'finalMetadata'])->findOrFail($submissionId);

            if (!$submission->acceptance_certificate_file_id) {
                return redirect()->back()->with('error', __('Certificate not generated yet.'));
            }

            // Regenerate certificate with updated data
            $certificateFileId = $this->generateFinalAcceptanceCertificate($submission);
            if ($certificateFileId) {
                // Archive old certificate in history
                if ($submission->acceptance_certificate_file_id) {
                    try {
                        \App\Models\CertificateHistory::create([
                            'client_order_submission_id' => $submission->id,
                            'file_id' => $submission->acceptance_certificate_file_id,
                            'is_active' => false,
                            'issued_by' => auth()->id(),
                            'issued_at' => now(),
                        ]);
                    } catch (\Exception $e) {
                        // If certificate_histories table doesn't exist, log but continue
                        Log::warning('Could not archive certificate to history: ' . $e->getMessage());
                    }
                }

                $submission->acceptance_certificate_file_id = $certificateFileId;
                $submission->save();

                DB::commit();
                return redirect()->route('admin.submissions.final-acceptance-certificates.index')
                    ->with('success', __('Certificate updated successfully.'));
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', __('Failed to update certificate.'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update certificate error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred.'));
        }
    }

    public function resendCertificate(Request $request, $submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with([
                'authors',
                'journal',
                'client_order.client'
            ])->findOrFail($submissionId);

            if (!$submission->acceptance_certificate_file_id) {
                return redirect()->back()->with('error', __('Certificate not generated yet.'));
            }

            $brevoService = new BrevoService();
            $authorEmails = [];

            if ($submission->has_author && $submission->authors) {
                foreach ($submission->authors as $author) {
                    if (!empty($author->email)) {
                        $authorEmails[] = $author->email;
                    }
                }
            }

            if (empty($authorEmails) && $submission->client_order && $submission->client_order->client) {
                $authorEmails[] = $submission->client_order->client->email;
            }

            if (empty($authorEmails)) {
                return redirect()->back()->with('error', __('No author email found.'));
            }

            $emailSubject = __('Final Acceptance Certificate - Resent');
            $emailBody = $this->buildCertificateEmail($submission);

            $brevoService->sendEmail(
                implode(',', $authorEmails),
                null,
                $emailSubject,
                $emailBody
            );

            // Update certificate sent timestamp
            $submission->certificate_sent_at = now();
            $submission->save();

            return redirect()->back()->with('success', __('Certificate resent successfully.'));
        } catch (\Exception $e) {
            Log::error('Resend certificate error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred while resending certificate.'));
        }
    }

    public function sendMetadataReminder(Request $request, $submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with([
                'authors',
                'client_order.client'
            ])->findOrFail($submissionId);

            if ($submission->metadata_status === 'approved') {
                return redirect()->back()->with('error', __('Metadata has already been approved.'));
            }

            $brevoService = new BrevoService();
            $authorEmails = [];

            if ($submission->has_author && $submission->authors) {
                foreach ($submission->authors as $author) {
                    if (!empty($author->email)) {
                        $authorEmails[] = $author->email;
                    }
                }
            }

            if (empty($authorEmails) && $submission->client_order && $submission->client_order->client) {
                $authorEmails[] = $submission->client_order->client->email;
            }

            if (empty($authorEmails)) {
                return redirect()->back()->with('error', __('No author email found.'));
            }

            $emailSubject = __('Reminder: Complete Final Metadata');
            $emailBody = $this->buildMetadataReminderEmail($submission);

            $brevoService->sendEmail(
                implode(',', $authorEmails),
                null,
                $emailSubject,
                $emailBody
            );

            return redirect()->back()->with('success', __('Reminder sent successfully.'));
        } catch (\Exception $e) {
            Log::error('Send metadata reminder error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred while sending reminder.'));
        }
    }

    private function buildCertificateEmail($submission)
    {
        $certificateUrl = route('admin.submissions.final-acceptance-certificate.download', encrypt($submission->id));

        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $body .= '<h2 style="color: #28a745;">' . __('Final Acceptance Certificate') . '</h2>';
        $body .= '<p>' . __('Dear Author,') . '</p>';
        $body .= '<p>' . __('We are pleased to inform you that your final acceptance certificate is ready.') . '</p>';
        $body .= '<p><strong>' . __('Article Title') . ':</strong> ' . e($submission->article_title ?? __('N/A')) . '</p>';
        $body .= '<p><strong>' . __('Journal') . ':</strong> ' . e($submission->journal->title ?? __('N/A')) . '</p>';
        $body .= '<p><a href="' . $certificateUrl . '" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 15px;">' . __('Download Certificate') . '</a></p>';
        $body .= '<hr><p style="color: #666; font-size: 12px;">' . __('This is an automated message from the editorial team.') . '</p>';
        $body .= '</body></html>';
        return $body;
    }

    private function buildMetadataReminderEmail($submission)
    {
        $metadataUrl = route('user.submission.final-metadata.form', encrypt($submission->id));

        $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $body .= '<h2 style="color: #ffc107;">' . __('Reminder: Complete Final Metadata') . '</h2>';
        $body .= '<p>' . __('Dear Author,') . '</p>';
        $body .= '<p>' . __('This is a reminder that your paper has been accepted and you need to complete the final metadata form.') . '</p>';
        $body .= '<p><strong>' . __('Article Title') . ':</strong> ' . e($submission->article_title ?? __('N/A')) . '</p>';
        $body .= '<p><strong>' . __('Journal') . ':</strong> ' . e($submission->journal->title ?? __('N/A')) . '</p>';
        $body .= '<p>' . __('Please complete the final metadata form to proceed with the certificate generation.') . '</p>';
        $body .= '<p><a href="' . $metadataUrl . '" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 15px;">' . __('Complete Final Metadata') . '</a></p>';
        $body .= '<hr><p style="color: #666; font-size: 12px;">' . __('This is an automated message from the editorial team.') . '</p>';
        $body .= '</body></html>';
        return $body;
    }
}
