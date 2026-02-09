<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\FinalCertificateServices;
use App\Http\Services\ReviewerCertificateServices;
use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\ClientOrderSubmission;
use App\Models\FinalCertificate;
use App\Models\ReviewerCertificate;
use App\Models\SubmissionReviewerNotes;
use App\Models\User;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{

    public $finalCertificateServices;
    public $reviewerCertificateServices;

    public function __construct()
    {
        $this->finalCertificateServices = new FinalCertificateServices();
        $this->reviewerCertificateServices = new ReviewerCertificateServices();

    }

    public function primary()
    {
        $data['activePrimaryCert'] = 'active';
        return view('admin.certificate.primary.index', $data);
    }

    // final certificate
    public function finalCertificateList(Request $request)
    {
        if ($request->ajax()) {
            return $this->finalCertificateServices->getFinalCertificateListData($request);
        } else {
            $data['pageTitle'] = __('Final Certificate list');
            $data['activeFinalCert'] = 'active';
            $finalCertQuery = FinalCertificate::where('status', STATUS_ACTIVE);
            if(auth()->user()->role == USER_ROLE_PUBLISHER){
                $finalCertQuery->whereHas('client_order.client_order_submission', function ($query) {
                    $query->whereIn('approval_status', [
                        SUBMISSION_ORDER_STATUS_ACCEPTED,
                        SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
                    ]);
                });
            }

            $data['finalCertList'] = $finalCertQuery->get();
            return view('admin.certificate.final.list', $data);

        }
        return view('admin.certificate.final.list', $data);
    }

    public function finalCertificateAdd()
    {
        $data['pageTitleParent'] = __('Final Certificate');
        $data['pageTitle'] = __('Add Final Certificate');
        $data['activeFinalCert'] = 'active';
        $data['orderList'] = ClientOrder::with('client_order_submission')
        ->whereHas('client_order_submission',function ($query){
            $query->whereIn('approval_status', [
                SUBMISSION_ORDER_STATUS_ACCEPTED,
               // SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
            ]);
        })->whereNull('deleted_at')->get();

        return view('admin.certificate.final.add', $data);
    }

    public function finalCertificateStore(Request $request)
    {

        return $this->finalCertificateServices->storeFinalCertificate($request);

    }

    public function finalCertificateEdit($id){

        $data['pageTitleParent'] = __('Final Certificate');
        $data['pageTitle'] = __('Edit Final Certificate');
        $data['activeFinalCert'] = 'active';
        $data['orderList'] = ClientOrder::with('client_order_submission')
        ->whereHas('client_order_submission',function ($query){
            $query->whereIn('approval_status', [
                SUBMISSION_ORDER_STATUS_ACCEPTED,
                //SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
            ]);
        })
        ->whereNull('deleted_at')->get();
        $data['finalCertificate'] = FinalCertificate::find(decrypt($id));
        return view('admin.certificate.final.edit', $data);
    }

    public function finalCertificatePrint($id){

        //For Arabic support
        $Arabic = new Arabic();
        $language = selectedLanguage();
        $isLanguageArabic = false;
        $isArabic = false;
        if ($language->iso_code == 'ar') {
            $isLanguageArabic = true;
            $isArabic = true;
        }


        $data['finalCertificate'] = FinalCertificate::find(decrypt($id));
        $data = [
            'author' => $data['finalCertificate']->author_names,
            'affiliation' => $data['finalCertificate']->author_affiliations,
            'paper_title' => $data['finalCertificate']->paper_title,
            'journal_name' => $data['finalCertificate']->journal_name,
            'volume' => $data['finalCertificate']->volume,
            'issue' => $data['finalCertificate']->issue,
            'date' => $data['finalCertificate']->date,
            'order_id' => $data['finalCertificate']->client_order_id,
            'ref_no' => $data['finalCertificate']->client_order_id,
            'signature' => 'Dr. Jane Smith',
        ];

        //dynamic data for Arabic support
        foreach($data as $key => $value) {
            if (preg_match('/[\x{0600}-\x{06FF}]/u', $value)) {
                    $data[$key]= $Arabic->utf8Glyphs($value);
            }
        }

        // For Arabic support
        if($isLanguageArabic){
            $staticData = [
                'headerOne' => 'شهادة القبول النهائي',
                'paraOne' => 'هذه الشهادة تمنح إلى',
                'paraTwo' => 'من',
                'paraThree' => 'لنشر البحث العلمي المعنون:',
                'journalInfoParaOne' => 'نشر في',
                'journalInfoParaTwo' => 'المجلد',
                'journalInfoParaThree' => 'العدد',
                'journalInfoParaFour' => 'التاريخ',
                'signaturePara' => 'رئيس هيئة التحرير',
            ];

            foreach ($staticData as $key => $value) {
                $data[$key . 'Static'] = $Arabic->utf8Glyphs($value);
            }
        }


        $pdf = Pdf::loadView('admin.certificate.final.details', $data);
        $pdf->setPaper('A4', 'portrait');

        // For better Arabic support
        $pdf->setOption('defaultFont', 'arabicfont');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isPhpEnabled', true);
        $pdf->setOption('isFontSubsettingEnabled', true);

        return $pdf->stream('certificate.pdf');
    }


    public function finalCertificateSend($id){

        $finalCertificate = FinalCertificate::find(decrypt($id));
        $clientOrder = ClientOrder::where('order_id', $finalCertificate->client_order_id)->first();

        finalCertificateEmailNotifyForCustomer($finalCertificate->client_order_id);
        FinalCertificateNotifyForCustomer($finalCertificate->client_order_id);

        return response()->json(['status' => true, 'message' => 'Certificate sent successfully']);
    }

    public function finalCertificateOrderDetails(Request $request)
    {

        try{

            $clientOrderSubmission = ClientOrderSubmission::with('authors','journal')->where('client_order_id', $request->order_id)->first();


            if($request->type == 'edit'){
                $finalCertificate = FinalCertificate::where('client_order_id', $clientOrderSubmission->order_id)->first();
                if($finalCertificate){
                    return response()->json([
                        'author_names' => $finalCertificate->author_names,
                        'author_affiliations' => $finalCertificate->author_affiliations,
                        'journal_name' => $finalCertificate->journal_name,
                        'paper_title' => $finalCertificate->paper_title
                    ]);
                }

            }

            $authors = $clientOrderSubmission->authors;

            // $affiliations = [];
            // foreach($authors as $author){
            //     $affiliations = array_merge($affiliations, json_decode($author->affiliation, true));
            // }
            // $affiliations = array_unique($affiliations);
            // $affiliations = implode(', ', $affiliations);

            $affiliationGroups = [];

            foreach ($authors as $author) {
                $decoded = json_decode($author->affiliation, true);

                if (is_array($decoded)) {
                    if (isset($decoded[0]) && is_string($decoded[0])) {
                        $affiliationGroups[] = implode(', ', $decoded);
                    }
                    elseif (isset($decoded[0]) && is_array($decoded[0])) {
                        foreach ($decoded as $item) {
                            $affiliationGroups[] = implode(', ', array_values($item));
                        }
                    }
                }
            }


            if (count($affiliationGroups) > 1) {
                $affiliations = implode(' | ', $affiliationGroups);
            } else {
                $affiliations = implode('', $affiliationGroups);
            }


            $authors = $authors->map(function ($author) {
                return $author->first_name . ' ' . $author->last_name;
            });

            $authors = implode(', ', $authors->toArray());

            //journal title
            $journal = $clientOrderSubmission->journal;
            $journal = $journal->title;


            $paper_title = $clientOrderSubmission->article_title;


            return response()->json([
                'author_names' => $authors,
                'journal_name' => $journal,
                'author_affiliations' => $affiliations,
                'paper_title' => $paper_title
            ]);
        }catch(\Exception $e){
            $errorMsg = $e->getMessage();
            return response()->json(['error' => $errorMsg]);
        }

    }

    //reviewer certificate
    public function reviewerCertificateList(Request $request)
    {
        if ($request->ajax()) {
            return $this->reviewerCertificateServices->getReviewerCertificateListData($request);
        } else {
            $data['pageTitle'] = __('Reviewer Certificate list');
            $data['activeReviewerCert'] = 'active';

            $reviewerCertQuery = ReviewerCertificate::where('status', STATUS_ACTIVE);
            if(auth()->user()->role == USER_ROLE_PUBLISHER){
                $reviewerCertQuery->whereHas('client_order.client_order_submission', function ($query) {
                    $query->whereIn('approval_status', [
                        SUBMISSION_ORDER_STATUS_ACCEPTED,
                        SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
                    ]);
                });
            }
            $data['reviewerCertList'] = $reviewerCertQuery->get();
            return view('admin.certificate.reviewer.list', $data);

        }
        return view('admin.certificate.reviewer.list', $data);
    }

    public function reviewerCertificateAdd()
    {
        $data['pageTitleParent'] = __('Reviewer Certificate');
        $data['pageTitle'] = __('Add Reviewer Certificate');
        $data['activeReviewerCert'] = 'active';
        $data['orderList'] = ClientOrder::with('client_order_submission')
        ->whereHas('client_order_submission',function ($query){
            $query->whereIn('approval_status', [
                SUBMISSION_ORDER_STATUS_ACCEPTED,
                //SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
            ]);
        })
        ->where(function($q) {
            $q->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('submission_reviewer_notes')
                    ->whereColumn('submission_reviewer_notes.order_id', 'client_orders.order_id')
                    ->whereExists(function($subQuery) {
                        $subQuery->select(DB::raw(1))
                            ->from('client_order_assignees')
                            ->whereColumn('client_order_assignees.order_id', 'client_orders.id')
                            ->whereColumn('client_order_assignees.assigned_to', 'submission_reviewer_notes.created_by')
                            ->whereNull('client_order_assignees.deleted_at');
                    });
            })->orWhereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('reviews')
                    ->whereColumn('reviews.client_order_id', 'client_orders.order_id')
                    ->where('reviews.status', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED);
            });
        })
        ->whereNull('deleted_at')->get();


        return view('admin.certificate.reviewer.add', $data);
    }

    public function reviewerCertificateStore(Request $request)
    {

        return $this->reviewerCertificateServices->storeReviewerCertificate($request);

    }

    public function reviewerCertificateEdit($id){

        $data['pageTitleParent'] = __('Reviewer Certificate');
        $data['pageTitle'] = __('Edit Reviewer Certificate');
        $data['activeReviewerCert'] = 'active';
        $reviewerCertificate = ReviewerCertificate::with('reviewer')->find(decrypt($id));
        $currentOrderId = $reviewerCertificate->client_order_id;

        $data['orderList'] = ClientOrder::with('client_order_submission')
        // ->whereHas('client_order_submission',function ($query){
        //     $query->whereIn('approval_status', [
        //         SUBMISSION_ORDER_STATUS_ACCEPTED,
        //         //SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
        //     ]);
        // })
        ->where(function($q) use ($currentOrderId) {
            $q->where(function($qq) {
                $qq->whereExists(function($query) {
                    $query->select(DB::raw(1))
                        ->from('submission_reviewer_notes')
                        ->whereColumn('submission_reviewer_notes.order_id', 'client_orders.order_id')
                        ->whereExists(function($subQuery) {
                            $subQuery->select(DB::raw(1))
                                ->from('client_order_assignees')
                                ->whereColumn('client_order_assignees.order_id', 'client_orders.id')
                                ->whereColumn('client_order_assignees.assigned_to', 'submission_reviewer_notes.created_by')
                                ->whereNull('client_order_assignees.deleted_at');
                        });
                })->orWhereExists(function($query) {
                    $query->select(DB::raw(1))
                        ->from('reviews')
                        ->whereColumn('reviews.client_order_id', 'client_orders.order_id')
                        ->where('reviews.status', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED);
                });
            })->orWhere('order_id', $currentOrderId);
        })
        ->whereNull('deleted_at')->get();
        $data['reviewerCertificate'] = $reviewerCertificate;

        $reviewerCertificate = $data['reviewerCertificate'];
        $clientOrder = ClientOrder::where('order_id', $reviewerCertificate->client_order_id)->first();
        if ($clientOrder) {
            $assigneeToIds = ClientOrderAssignee::where('order_id', $clientOrder->id)->pluck('assigned_to')->toArray();

            $data['reviewerList'] = User::whereRole(USER_ROLE_REVIEWER)->get();

            // where(function($q) use ($assigneeToIds, $reviewerCertificate) {
            //         $q->whereIn('id', $assigneeToIds)
            //           ->where('role', USER_ROLE_REVIEWER)
            //           ->where(function($query) use ($reviewerCertificate) {
            //               $query->whereHas('reviewerNotes', function($q) use ($reviewerCertificate) {
            //                   $q->where('order_id', $reviewerCertificate->client_order_id);
            //               })->orWhereHas('reviews', function($q) use ($reviewerCertificate) {
            //                   $q->where('client_order_id', $reviewerCertificate->client_order_id)
            //                     ->where('status', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED);
            //               });
            //           });
            //     })
            //     // ->orWhere('id', $reviewerCertificate->reviewer_id)
            //     ->
        } else {
            $data['reviewerList'] = User::where('id', $reviewerCertificate->reviewer_id)->get();
        }

        // dd($data);
        return view('admin.certificate.reviewer.edit', $data);
    }

    public function reviewerCertificatePrint($id){

        $cert = ReviewerCertificate::with('reviewer')->find(decrypt($id));
        if (!$cert) {
            abort(404);
        }

        $Arabic = new Arabic();
        $isLanguageArabic = ($cert->language == 'ar');

        $sigFile = $cert->signature_image ? \App\Models\FileManager::find($cert->signature_image) : null;
        $logoFile = $cert->logo_image ? \App\Models\FileManager::find($cert->logo_image) : null;

        $data = [
            'title' => $cert->title,
            'reviewer_name' => $cert->reviewer->name,
            'affiliations' => $cert->affiliations,
            'paper_title' => $cert->paper_title,
            'journal_name' => $cert->journal_name,
            'order_id' => $cert->client_order_id,
            'date' => $cert->created_at->format('d/m/Y'),
            'ref_no' => 'AJSRP/' . $cert->client_order_id . '/' . $cert->created_at->format('Y'),
            'signature' => $isLanguageArabic ? ($cert->chief_editor_name_ar ?: $cert->chief_editor_name ?: 'Dr. Jane Smith') : ($cert->chief_editor_name ?: 'Dr. Jane Smith'),
            'isArabic' => $isLanguageArabic,
            'signature_image' => ($sigFile && $sigFile->path) ? public_path('storage/' . $sigFile->path) : null,
            'logo_image' => ($logoFile && $logoFile->path) ? public_path('storage/' . $logoFile->path) : null,
        ];

        //dynamic data for Arabic support
        foreach($data as $key => $value) {
            if ($value && is_string($value) && preg_match('/[\x{0600}-\x{06FF}]/u', $value)) {
                    $data[$key]= $Arabic->utf8Glyphs($value);
            }
        }

        // For Arabic support
        if($isLanguageArabic){
            $staticData = [
                'headerOne' => 'شهادة شكر للمحكم',
                'paraOne' => 'نقدّر ونشكر',
                'paraTwo' => 'من',
                'paraThree' => 'لقيامه بمراجعة المخطوطة المعنونة',
                'journalInfoParaOne' => 'المقدمة إلى',
                'signaturePara' => 'رئيس هيئة التحرير',
                'labelDate' => 'التاريخ',
                'labelRefNo' => 'الرقم المرجعي',
            ];

            foreach ($staticData as $key => $value) {
                $data[$key . 'Static'] = $Arabic->utf8Glyphs($value);
            }
        }

        $pdf = Pdf::loadView('admin.certificate.reviewer.details', $data);
        $pdf->setPaper('A4', 'portrait');

         // For better Arabic support
        $pdf->setOption('defaultFont', 'arabicfont');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isPhpEnabled', true);
        $pdf->setOption('isFontSubsettingEnabled', true);


        return $pdf->stream('certificate.pdf');
    }


    public function reviewerCertificateSend($id){

        $reviewerCertificate = ReviewerCertificate::find(decrypt($id));
        $clientOrder = ClientOrder::where('order_id', $reviewerCertificate->client_order_id)->first();
        $userData = User::where('id', $reviewerCertificate->reviewer_id)->first();

        reviewerCertificateEmailNotifyForCustomer($reviewerCertificate->client_order_id, $userData);
        ReviewerCertificateNotifyForCustomer($reviewerCertificate->client_order_id, $userData);

        return response()->json(['status' => true, 'message' => 'Certificate sent successfully']);
    }

    public function reviewerCertificateOrderDetails(Request $request)
    {

        try{

            $clientOrder = ClientOrder::where('order_id', $request->order_id)->first();

            $clientOrderSubmission = ClientOrderSubmission::with('authors','journal')->where('client_order_id', $request->order_id)->first();

            $assigneeToIds = ClientOrderAssignee::where('order_id', $clientOrder->id)->pluck('assigned_to')->toArray();

            $reviewers = User::whereIn('id', $assigneeToIds)
                ->where('role', USER_ROLE_REVIEWER)
                ->where(function($query) use ($request) {
                    $query->whereHas('reviewerNotes', function($q) use ($request) {
                        $q->where('order_id', $request->order_id);
                    })->orWhereHas('reviews', function($q) use ($request) {
                        $q->where('client_order_id', $request->order_id)
                          ->where('status', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED);
                    });
                })
                ->get();


            // if($request->type == 'edit'){
            //     $reviewerCertificate = ReviewerCertificate::where('client_order_id', $clientOrderSubmission->order_id)->first();
            //     if($reviewerCertificate){
            //         return response()->json([
            //             'reviewers' => $reviewers,
            //             'title' => $reviewerCertificate->title,
            //             'affiliations' => $reviewerCertificate->author_affiliations,
            //             'journal_name' => $reviewerCertificate->journal_name,
            //             'paper_title' => $reviewerCertificate->paper_title,
            //         ]);
            //     }

            // }

            $authors = $clientOrderSubmission->authors;

            // $affiliations = [];
            // foreach($authors as $author){
            //     $affiliations = array_merge($affiliations, json_decode($author->affiliation, true));
            // }
            // $affiliations = array_unique($affiliations);
            // $affiliations = implode(', ', $affiliations);

            $affiliationGroups = [];

            foreach ($authors as $author) {
                $decoded = json_decode($author->affiliation, true);

                if (is_array($decoded)) {
                    if (isset($decoded[0]) && is_string($decoded[0])) {
                        $affiliationGroups[] = implode(', ', $decoded);
                    }
                    elseif (isset($decoded[0]) && is_array($decoded[0])) {
                        foreach ($decoded as $item) {
                            $affiliationGroups[] = implode(', ', array_values($item));
                        }
                    }
                }
            }


            if (count($affiliationGroups) > 1) {
                $affiliations = implode(' | ', $affiliationGroups);
            } else {
                $affiliations = implode('', $affiliationGroups);
            }


            $authors = $authors->map(function ($author) {
                return $author->first_name . ' ' . $author->last_name;
            });

            $authors = implode(', ', $authors->toArray());

            //journal title
            $journal = $clientOrderSubmission->journal;
            $journal = $journal->title;


            $paper_title = $clientOrderSubmission->article_title;


            return response()->json([
                'reviewers' => $reviewers,
                'affiliations' => $affiliations,
                'journal_name' => $journal,
                'paper_title' => $paper_title
            ]);
        }catch(\Exception $e){
            $errorMsg = $e->getMessage();
            return response()->json(['error' => $errorMsg]);
        }

    }
}
