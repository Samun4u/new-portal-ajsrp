<?php

namespace App\Http\Controllers;

use App\Models\FinalCertificate;
use App\Models\JournalSignature;
use App\Models\Journal;
use App\Models\ClientOrderSubmission;
use App\Models\Issue;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS2D;
use Illuminate\Support\Carbon;

class CertificateController extends Controller
{
    /**
     * Display the certificate generation form
     */
    public function create($submissionId)
    {
        $submission = ClientOrderSubmission::with(['authors', 'journal', 'issue'])->findOrFail($submissionId);
        $journals = Journal::all();

        // Construct journalsDB for JS
        $journalsDB = [];
        foreach ($journals as $journal) {
            // Assuming journal has 'slug' as abbrev or similar.
            $abbrev = $journal->slug ?? $journal->id;
            $signature = JournalSignature::where('journal_abbrev', $abbrev)->first();

            $journalsDB[$abbrev] = [
                'id' => $journal->id,
                'name' => $journal->title ?? $journal->name,
                'nameAr' => $journal->arabic_title ?? $journal->title,
                'abbrev' => $abbrev,
                'issn' => $journal->issn ?? '',
                'editor' => $signature ? $signature->chief_editor_name : ($journal->chief_editor ?? 'Chief Editor'),
                'editorAr' => $signature ? $signature->chief_editor_name_ar : 'رئيس التحرير',
                'managingEditor' => $signature ? $signature->managing_editor_name : '',
                'managingEditorAr' => $signature ? $signature->managing_editor_name_ar : 'مدير التحرير',
            ];
        }

        return view('certificates.create', compact('submission', 'journals', 'journalsDB'));
    }

    /**
     * Upload signature or stamp for a journal
     */
    public function uploadSignature(Request $request)
    {
        $request->validate([
            'journal_abbrev' => 'required|string',
            'type' => 'required|in:signature,stamp',
            'file' => 'required|image|max:2048',
        ]);

        $signature = JournalSignature::firstOrCreate(
            ['journal_abbrev' => $request->journal_abbrev],
            ['chief_editor_name' => 'Chief Editor']
        );

        $path = $request->file('file')->store('journal-signatures/' . $request->journal_abbrev, 'public');

        if ($request->type === 'signature') {
            if ($signature->signature_path && Storage::disk('public')->exists($signature->signature_path)) {
                Storage::disk('public')->delete($signature->signature_path);
            }
            $signature->signature_path = $path;
        } else {
            if ($signature->stamp_path && Storage::disk('public')->exists($signature->stamp_path)) {
                Storage::disk('public')->delete($signature->stamp_path);
            }
            $signature->stamp_path = $path;
        }

        $signature->save();

        return response()->json([
            'success' => true,
            'url' => Storage::url($path),
        ]);
    }

    /**
     * Generate and store certificate
     */
    public function generate(Request $request)
    {
        $request->validate([
            'submission_id' => 'required',
            'journal_abbrev' => 'required|string',
            'volume' => 'required',
            'issue' => 'required',
            'language' => 'required|in:english,arabic',
            'doi' => 'nullable|string',
        ]);

        $submission = ClientOrderSubmission::findOrFail($request->submission_id);
        $clientOrderId = $submission->client_order_id ?? $submission->id;

        // --- 1. Signature & Stamp Management ---
        $journalAbbrev = trim($request->journal_abbrev);
        $signatureRecord = JournalSignature::firstOrNew(['journal_abbrev' => $journalAbbrev]);

        // Update Chief Editor Name if provided
        if ($request->filled('chief_editor_name')) {
            $signatureRecord->chief_editor_name = $request->chief_editor_name;
        }
        if ($request->filled('chief_editor_name_ar')) {
            $signatureRecord->chief_editor_name_ar = $request->chief_editor_name_ar;
        }

        // Update Managing Editor Name if provided
        if ($request->filled('managing_editor_name')) {
            $signatureRecord->managing_editor_name = $request->managing_editor_name;
        }
        if ($request->filled('managing_editor_name_ar')) {
            $signatureRecord->managing_editor_name_ar = $request->managing_editor_name_ar;
        }

        // Handle File Uploads (Persistent)
        if ($request->hasFile('signature_file')) {
            $path = $request->file('signature_file')->store('signatures', 'public');
            $signatureRecord->signature_path = $path;
        }
        if ($request->hasFile('managing_editor_signature_file')) {
            $path = $request->file('managing_editor_signature_file')->store('signatures', 'public');
            $signatureRecord->managing_editor_signature_path = $path;
        }
        if ($request->hasFile('stamp_file')) {
            $path = $request->file('stamp_file')->store('stamps', 'public');
            $signatureRecord->stamp_path = $path;
        }
        $signatureRecord->save(); // Save changes to journal_signatures

        // --- 2. Generate Certificate Data ---
        $certificateNumber = $request->certificate_number ?? ($request->journal_abbrev . '-' . date('Y') . '-' . mt_rand(1000, 9999));

        // Verification URL
        $verifyUrl = route('certificates.verify', $certificateNumber);
        $qrCode = (new DNS2D)->getBarcodeSVG($verifyUrl, 'QRCODE', 4, 4);

        $journal = Journal::where('slug', $request->journal_abbrev)->orWhere('id', $submission->journal_id)->first();

        // Prepare view data
        $viewData = [
            'submission' => $submission,
            'journal' => $journal,
            'certNum' => $certificateNumber,
            'volume' => $request->volume,
            'issue' => $request->issue,
            'doi' => $request->doi, // Manual DOI
            'date' => $request->publication_date ? date('F j, Y', strtotime($request->publication_date)) : date('F j, Y'),
            'qrCode' => $qrCode,
            'verifyUrl' => $verifyUrl,
            'chiefEditor' => $request->language == 'arabic' ? ($signatureRecord->chief_editor_name_ar ?? 'رئيس التحرير') : ($signatureRecord->chief_editor_name ?? 'Chief Editor'),
            'managingEditor' => $request->language == 'arabic' ? ($signatureRecord->managing_editor_name_ar ?? 'مدير التحرير') : ($signatureRecord->managing_editor_name ?? 'Managing Editor'),
            'signatureImage' => $signatureRecord->signature_path && Storage::disk('public')->exists($signatureRecord->signature_path)
                ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($signatureRecord->signature_path))
                : null,
            'managingSignatureImage' => $signatureRecord->managing_editor_signature_path && Storage::disk('public')->exists($signatureRecord->managing_editor_signature_path)
                ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($signatureRecord->managing_editor_signature_path))
                : null,
            'stampImage' => $signatureRecord->stamp_path && Storage::disk('public')->exists($signatureRecord->stamp_path)
                ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($signatureRecord->stamp_path))
                : null,
            'certificate' => (object) [
                'certificate_number' => $certificateNumber,
                'paper_title' => $submission->article_title,
                'author_names' => $request->author_names ?? ($submission->authors->pluck('name')->implode(', ') ?: $submission->corresponding_author_name),
                'client_order_id' => $clientOrderId,
                'volume' => $request->volume,
                'issue' => $request->issue,
                'doi' => $request->doi,
                'date' => $request->publication_date ?? now(),
            ]
        ];

        // --- 3. Render PDF Server Side (DISABLED) ---
        // $template = $request->language == 'arabic' ? 'certificates.templates.certificate-arabic' : 'certificates.templates.certificate-english';
        // $pdf = Pdf::loadView($template, $viewData)->setPaper('a4', 'landscape');

        // // Save to storage
        // $filename = 'certificate-' . $certificateNumber . '-' . time() . '.pdf';
        // $savePath = 'certificates/pdfs/' . $filename;
        // Storage::disk('public')->put($savePath, $pdf->output());

        $savePath = null; // No PDF file stored on server

        // --- 4. Save/Update FinalCertificate Record ---
        // Use explicitly provided journal name if available (from frontend)
        // Check both 'journal_name' (added just now) and 'journal_abbrev'
        $resolvedJournalName = $request->journal_name ?? ($journal->title ?? $request->journal_abbrev);

        $certRecord = FinalCertificate::updateOrCreate(
            ['client_order_id' => $clientOrderId],
            [
                'journal_name' => $resolvedJournalName,
                'volume' => $request->volume,
                'issue' => $request->issue,
                'language' => $request->language,
                'certificate_number' => $certificateNumber,
                'author_names' => $viewData['certificate']->author_names,
                'paper_title' => $submission->article_title,
                'date' => $viewData['certificate']->date,
                'doi' => $request->doi,
                'pdf_path' => $savePath,
                'signature_path' => $signatureRecord->signature_path,
                'managing_editor_signature_path' => $signatureRecord->managing_editor_signature_path,
                'stamp_path' => $signatureRecord->stamp_path,
                'chief_editor' => $signatureRecord->chief_editor_name,
                'chief_editor_ar' => $signatureRecord->chief_editor_name_ar,
                'managing_editor' => $signatureRecord->managing_editor_name,
                'managing_editor_ar' => $signatureRecord->managing_editor_name_ar,
            ]
        );

        return response()->json([
            'success' => true,
            'certificate_id' => $certRecord->id,
            'view_url' => route('certificates.view', $certRecord->id),
            'download_url' => route('certificates.download', $certRecord->id)
        ]);
    }

    public function download($id)
    {
        $certificate = FinalCertificate::find($id);

        if (!$certificate) {
            // Try searching by client_order_id (encrypted or plain) or submission id logic
            try {
                $decrypted = decrypt($id);
                $certificate = FinalCertificate::where('client_order_id', $decrypted)->first();
            } catch (\Exception $e) {
                // Not encrypted
            }

            if (!$certificate) {
                // Try by order ID string/int
                $submission = ClientOrderSubmission::find($id);
                if ($submission) {
                    $certificate = FinalCertificate::where('client_order_id', $submission->client_order_id)->first();
                    if (!$certificate) {
                        $certificate = FinalCertificate::where('client_order_id', $submission->id)->first();
                    }
                }
            }
        }

        if (!$certificate) {
            abort(404, 'Certificate not found');
        }

        // Reconstruct Data (Duplicate logic from view method to ensure consistency)
        $certRecord = $certificate;
        $submission = ClientOrderSubmission::where('client_order_id', $certRecord->client_order_id)
            ->orWhere('id', $certRecord->client_order_id)
            ->first();

        // Verification URL
        $verifyUrl = route('certificates.verify', $certRecord->certificate_number);
        // Use PNG for PDF compatibility instead of SVG if possible, or stick to SVG if it works.
        // DomPDF handles SVG reasonably well, but let's stick to what viewed logic used or verify compatibility.
        // The view used SVG. Let's try SVG first.
        $qrCode = (new DNS2D)->getBarcodeSVG($verifyUrl, 'QRCODE', 6, 6);

        // Get Journal
        $journal = Journal::where('title', $certRecord->journal_name)->orWhere('arabic_title', $certRecord->journal_name)->first();
        if (!$journal && $submission) {
            $journal = Journal::find($submission->journal_id);
        }
        if (!$journal) {
            $journal = (object) ['title' => $certRecord->journal_name, 'issn' => '', 'name' => $certRecord->journal_name];
        }

        // Prepare View Data
        $viewData = [
            'submission' => $submission,
            'journal' => $journal,
            'certNum' => $certRecord->certificate_number,
            'volume' => $certRecord->volume,
            'issue' => $certRecord->issue,
            'doi' => $certRecord->doi,
            'date' => $certRecord->date ? date('F j, Y', strtotime($certRecord->date)) : date('F j, Y'),
            'qrCode' => $qrCode,
            'verifyUrl' => $verifyUrl,
            'chiefEditor' => $certRecord->language == 'arabic' ? ($certRecord->chief_editor_ar ?? 'رئيس التحرير') : ($certRecord->chief_editor ?? 'Chief Editor'),
            'managingEditor' => $certRecord->language == 'arabic' ? ($certRecord->managing_editor_ar ?? 'مدير التحرير') : ($certRecord->managing_editor ?? 'Managing Editor'),
            // Encode images to Base64 for DomPDF (local paths might fail if not absolute or permission issues)
            'signatureImage' => $certRecord->signature_path && Storage::disk('public')->exists($certRecord->signature_path)
                ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($certRecord->signature_path))
                : null,
            'managingSignatureImage' => $certRecord->managing_editor_signature_path && Storage::disk('public')->exists($certRecord->managing_editor_signature_path)
                ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($certRecord->managing_editor_signature_path))
                : null,
            'stampImage' => $certRecord->stamp_path && Storage::disk('public')->exists($certRecord->stamp_path)
                ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($certRecord->stamp_path))
                : null,
            'certificate' => (object) [
                'certificate_number' => $certRecord->certificate_number,
                'paper_title' => $certRecord->paper_title,
                'author_names' => $certRecord->author_names,
                'client_order_id' => $certRecord->client_order_id,
                'volume' => $certRecord->volume,
                'issue' => $certRecord->issue,
                'doi' => $certRecord->doi,
                'date' => $certRecord->date,
            ]
        ];

        $template = $certRecord->language == 'arabic' ? 'certificates.templates.certificate-arabic' : 'certificates.templates.certificate-english';

        // Load PDF
        // $viewData['isPdf'] = true; // Add flag
        // $pdf = Pdf::loadView($template, $viewData)->setPaper('a4', 'portrait');
        // // Optional: set options if needed
        // $pdf->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        // return $pdf->download('Certificate-' . $certRecord->certificate_number . '.pdf');

        $viewData['isPdf'] = true;
        $pdf = Pdf::loadView($template, $viewData)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'sans-serif',
                // Disable auto-page breaking entirely
                'isAutoPageBreakEnabled' => false,
                // Remove all margins
                'margin_top' => 0,
                'margin_right' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0,
                // Use original dimensions
                'autoPageBreak' => false,
                'enable_html5_parser' => true,
            ]);

        return $pdf->download('Certificate-' . $certRecord->certificate_number . '.pdf');
    }

    public function getIssues($journalId)
    {
        // Fetch issues. Assuming Issue model and relationship.
        $issues = \App\Models\Issue::where('journal_id', $journalId)
            ->orderBy('publication_date', 'desc')
            ->get()
            ->map(function ($issue) {
                return [
                    'id' => $issue->id,
                    'volume' => $issue->volume,
                    'number' => $issue->number,
                    'issue' => $issue->number,
                    'year' => $issue->year, // Added year if available
                    'title' => $issue->title, // Added title if available
                    'status' => $issue->status ?? 'published',
                    'articles_count' => $issue->articles ? $issue->articles->count() : 0, // Count articles
                    'publication_date' => $issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('Y-m-d') : 'N/A',
                    'selectable' => true
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'issues' => $issues
            ]
        ]);
    }

    public function verify($certificate_number)
    {
        $certificate = FinalCertificate::where('certificate_number', $certificate_number)->firstOrFail();
        return view('certificates.verify', compact('certificate'));
    }

    public function view($id)
    {
        $certRecord = FinalCertificate::findOrFail($id);
        $submission = ClientOrderSubmission::where('client_order_id', $certRecord->client_order_id)
            ->orWhere('id', $certRecord->client_order_id)
            ->first();

        // Reconstruct Data
        // Verification URL
        $verifyUrl = route('certificates.verify', $certRecord->certificate_number);
        $qrCode = (new DNS2D)->getBarcodeSVG($verifyUrl, 'QRCODE', 4, 4);

        // Get Journal (Try to find mostly for ISSN if needed, though we used name in record)
        // Note: FinalCertificate stores 'journal_name'. For ISSN, we might need to look up Journal model.
        // Try finding by name or abbrev if stored.
        $journal = Journal::where('title', $certRecord->journal_name)->orWhere('arabic_title', $certRecord->journal_name)->first();
        if (!$journal && $submission) {
            $journal = Journal::find($submission->journal_id);
        }

        // Fallback object if journal not found but name is there
        if (!$journal) {
            $journal = (object) ['title' => $certRecord->journal_name, 'issn' => '', 'name' => $certRecord->journal_name];
        }

        // Prepare View Data
        $viewData = [
            'submission' => $submission,
            'journal' => $journal,
            'certificateId' => $certRecord->id,
            'certNum' => $certRecord->certificate_number,
            'volume' => $certRecord->volume,
            'issue' => $certRecord->issue,
            'doi' => $certRecord->doi,
            'date' => $certRecord->date ? date('F j, Y', strtotime($certRecord->date)) : date('F j, Y'),
            'qrCode' => $qrCode,
            'verifyUrl' => $verifyUrl,
            'chiefEditor' => $certRecord->language == 'arabic' ? ($certRecord->chief_editor_ar ?? 'رئيس التحرير') : ($certRecord->chief_editor ?? 'Chief Editor'),
            'managingEditor' => $certRecord->language == 'arabic' ? ($certRecord->managing_editor_ar ?? 'مدير التحرير') : ($certRecord->managing_editor ?? 'Managing Editor'),
            'signatureImage' => $certRecord->signature_path && Storage::disk('public')->exists($certRecord->signature_path)
                ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($certRecord->signature_path))
                : null,
            'managingSignatureImage' => $certRecord->managing_editor_signature_path && Storage::disk('public')->exists($certRecord->managing_editor_signature_path)
                ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($certRecord->managing_editor_signature_path))
                : null,
            'stampImage' => $certRecord->stamp_path && Storage::disk('public')->exists($certRecord->stamp_path)
                ? 'data:image/png;base64,' . base64_encode(Storage::disk('public')->get($certRecord->stamp_path))
                : null,
            'certificate' => (object) [
                'certificate_number' => $certRecord->certificate_number,
                'paper_title' => $certRecord->paper_title,
                'author_names' => $certRecord->author_names,
                'client_order_id' => $certRecord->client_order_id,
                'volume' => $certRecord->volume,
                'issue' => $certRecord->issue,
                'doi' => $certRecord->doi,
                'date' => $certRecord->date,
            ]
        ];

        $viewData['isPdf'] = false; // Flag for view

        $template = $certRecord->language == 'arabic' ? 'certificates.templates.certificate-arabic' : 'certificates.templates.certificate-english';
        return view($template, $viewData);
    }
}
