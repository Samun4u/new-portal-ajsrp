<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Research;
use App\Models\PrimaryCertificate;
use App\Models\ClientOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ResearchSubmissionController extends Controller
{
    /**
     * Display a listing of research submissions.
     */
    public function index()
    {
        $data['pageTitle'] = __('Research Submissions');
        $data['activeResearchSubmission'] = 'active';
        $data['activeSidebarMenu'] = 'research-submission';
        return view('admin.research_submission.list', $data);
    }

    /**
     * Get data for DataTable.
     */
    public function getData(Request $request)
    {
        $query = Research::with(['user', 'approver', 'clientOrder', 'primaryCertificate', 'authors'])
            ->orderBy('created_at', 'desc')
            ->select('research.*');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('approval_status', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('author_name', function ($row) {
                $correspondingAuthor = $row->correspondingAuthor;
                return $correspondingAuthor ? $correspondingAuthor->name_en : 'N/A';
            })
            ->addColumn('title', function ($row) {
                $title = $row->english_title ?: $row->arabic_title;
                return strlen($title) > 50 ? substr($title, 0, 50) . '...' : $title;
            })
            ->addColumn('user', function ($row) {
                return $row->user ? $row->user->name : 'Guest';
            })
            ->addColumn('status', function ($row) {
                $badges = [
                    'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
                    'approved' => '<span class="badge bg-success">Approved</span>',
                    'rejected' => '<span class="badge bg-danger">Rejected</span>',
                ];
                return $badges[$row->approval_status] ?? '<span class="badge bg-secondary">Unknown</span>';
            })
            ->addColumn('language', function ($row) {
                if ($row->language === 'ar') {
                    return '<span class="badge bg-info">Arabic</span>';
                }
                return '<span class="badge bg-primary">English</span>';
            })
            ->addColumn('certificate_status', function ($row) {
                if ($row->primaryCertificate && $row->primaryCertificate->certificate_sent) {
                    return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Sent</span>';
                }
                return '<span class="badge bg-secondary"><i class="fas fa-clock"></i> Not Sent</span>';
            })
            ->addColumn('action', function ($row) {
                $viewBtn = '<a href="' . route('admin.research-submission.show', $row->id) . '" class="btn btn-sm btn-info me-1" title="' . __('View') . '"><i class="fas fa-eye"></i></a>';

                // Smart reviewer assignment button (opens modal)
                $assignBtn = '';
                if ($row->clientOrder) {
                    $title = $row->english_title ?: $row->arabic_title;
                    $safeTitle = e(strlen($title) > 80 ? substr($title, 0, 80) . '...' : $title);
                    $assignBtn = '<button type="button" class="btn btn-sm btn-primary me-1 assign-reviewers-btn"'
                        . ' data-order-id="' . $row->clientOrder->id . '"'
                        . ' data-title="' . $safeTitle . '"'
                        . ' title="' . __('Assign Reviewers') . '"><i class="fas fa-user-plus"></i></button>';
                }

                $approveBtn = '';
                $rejectBtn = '';

                if ($row->approval_status === 'pending') {
                    $approveBtn = '<button type="button" class="btn btn-sm btn-success me-1 approve-btn" data-id="' . $row->id . '" title="' . __('Approve') . '"><i class="fas fa-check"></i></button>';
                    $rejectBtn = '<button type="button" class="btn btn-sm btn-danger reject-btn" data-id="' . $row->id . '" title="' . __('Reject') . '"><i class="fas fa-times"></i></button>';
                }

                return $viewBtn . $assignBtn . $approveBtn . $rejectBtn;
            })
            ->filterColumn('title', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('english_title', 'like', "%{$keyword}%")
                      ->orWhere('arabic_title', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('author_name', function($query, $keyword) {
                $query->whereHas('authors', function($q) use ($keyword) {
                    $q->where('name_en', 'like', "%{$keyword}%")
                      ->orWhere('name_ar', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function($q) use ($search) {
                        $q->where('english_title', 'like', "%{$search}%")
                          ->orWhere('arabic_title', 'like', "%{$search}%")
                          ->orWhere('paper_id_en', 'like', "%{$search}%")
                          ->orWhere('paper_id_ar', 'like', "%{$search}%")
                          ->orWhereHas('authors', function($authorQuery) use ($search) {
                              $authorQuery->where('name_en', 'like', "%{$search}%")
                                          ->orWhere('name_ar', 'like', "%{$search}%")
                                          ->orWhere('email', 'like', "%{$search}%");
                          });
                    });
                }
            })
            ->rawColumns(['status', 'language', 'certificate_status', 'action'])
            ->make(true);
    }

    /**
     * Display the specified research submission.
     */
    public function show($id)
    {
        $data['pageTitle'] = __('Research Submission Details');
        $data['activeResearchSubmission'] = 'active';
        $data['activeSidebarMenu'] = 'research-submission';
        $data['research'] = Research::with(['authors', 'user', 'approver', 'clientOrder', 'primaryCertificate'])
            ->findOrFail($id);

        return view('admin.research_submission.show', $data);
    }

    /**
     * Approve a research submission and send certificate.
     */
    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $research = Research::findOrFail($id);

            // Update research approval status
            $research->approval_status = 'approved';
            $research->approved_at = now();
            $research->approved_by = auth()->id();
            $research->admin_notes = $request->input('notes', '');
            $research->save();

            // Send primary certificate if linked
            if ($research->client_order_id) {
                $primaryCertificate = PrimaryCertificate::where('client_order_id', $research->client_order_id)->first();

                if ($primaryCertificate && !$primaryCertificate->certificate_sent) {
                    // Update certificate status
                    $primaryCertificate->certificate_sent = true;
                    $primaryCertificate->sent_at = now();
                    $primaryCertificate->save();

                    // Get user language preference
                    $language = $research->language;

                    // Send certificate email and notification using bilingual templates
                    primaryCertificateEmailNotifyForCustomerBilingual($primaryCertificate->client_order_id, $language);
                    PrimaryCertificateNotifyForCustomerBilingual($primaryCertificate->client_order_id, $language);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('Research submission approved and certificate sent successfully.')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Research approval error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('An error occurred while approving the submission.')
            ], 500);
        }
    }

    /**
     * Reject a research submission.
     */
    public function reject(Request $request, $id)
    {
        try {
            $research = Research::findOrFail($id);

            $research->approval_status = 'rejected';
            $research->approved_at = now();
            $research->approved_by = auth()->id();
            $research->admin_notes = $request->input('notes', '');
            $research->save();

            // Notify user about rejection
            if ($research->user_id) {
                $language = $research->language;
                researchRejectionNotification($research->id, $language);
            }

            return response()->json([
                'success' => true,
                'message' => __('Research submission rejected.')
            ]);

        } catch (\Exception $e) {
            Log::error('Research rejection error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('An error occurred while rejecting the submission.')
            ], 500);
        }
    }
    /**
     * Download the research submission as a Word document.
     */
    public function downloadDocx($id, \App\Services\ResearchDocxService $docxService)
    {
        $research = Research::with(['authors'])->findOrFail($id);

        $researchData = [
            'research' => [
                'arabicTitle' => $research->arabic_title,
                'englishTitle' => $research->english_title,
                'science' => $research->field,
                'otherScience' => $research->other_field,
                'journal' => $research->journal,
                'keywords' => $research->keywords,
                'paperIdAr' => $research->paper_id_ar,
                'paperIdEn' => $research->paper_id_en,
                'thesisExtraction' => $research->thesis_answer,
            ],
            'authors' => []
        ];

        foreach ($research->authors as $author) {
            $researchData['authors'][] = [
                'nameEn' => $author->name_en,
                'nameAr' => $author->name_ar,
                'titleEn' => $author->title_en,
                'titleAr' => $author->title_ar,
                'email' => $author->email,
                'phone' => $author->phone,
                'degreeEn' => $author->degree_en,
                'degreeAr' => $author->degree_ar,
                'formattedAffiliationEn' => $author->affiliation_en,
                'formattedAffiliationAr' => $author->affiliation_ar,
                'orcid' => $author->orcid,
                'corresponding' => $author->is_corresponding,
            ];
        }

        $filePath = $docxService->generateDocx($researchData);

        if ($filePath && file_exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Could not generate document.');
    }
}

