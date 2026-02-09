<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ArticleType;
use App\Models\AuthorContributorRole;
use App\Models\AuthorDetails;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\ClientOrderSubmission;
use App\Models\ClientOrderSubmissionRevision;
use App\Models\ClientOrderSubmissionRevisionFile;
use App\Models\ClientOrderSubmissionDeclarations;
use App\Models\ClientOrderSubmissionFundingDetails;
use App\Models\ClientOrderSubmissionOpposedReviewers;
use App\Models\ClientOrderSubmissionReviewers;
use App\Models\ContributorRole;
use App\Models\Declarations;
use App\Models\Journal;
use App\Models\JournalSubject;
use App\Models\Service;
use App\Models\Reviews;
use App\Models\User;
use App\Traits\ResponseTrait;
use BitPaySDK\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\SupplymentMaterialFile;
use App\Models\FinalMetadata;
use App\Models\FileManager;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Rinvex\Country\CountryLoader;
use Rinvex\Country\Models\Country;

class SubmissionController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        Session::forget('selected_journal');
        $data['pageTitleParent'] = __('Service');
        $data['pageTitle'] = __('Service Details');
        $data['activeService'] = 'active';
        return view('user.submission.index', $data);
    }
    public function select_a_journal(Request $request, $by, $action = null, $id = null)
    {
        //Session::forget('selected_journal');
        // return $selectedJournal = Session::get('selected_journal');

        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';

        $data['journals'] = Journal::where('status', 'active')->get();
        $data['journalSubjects'] = JournalSubject::where('status', 'active')->get();

        $data['step'] = "stepOne";
        $data['by'] = $by;

        $data['service'] = Service::where('id', 1)->first();

        if (
            $id
        ) {
            $clientOrder = ClientOrder::where('order_id', $id)->first();
            $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();

            $data['action'] = 'update';
            $data['clientOrderId'] = $id;
            $data['clientOrder'] = $clientOrder;
            $data['clientOrderSubmission'] = $clientOrderSubmission;
            $data['selectedJournal'] = Journal::find($clientOrderSubmission->journal_id);

        } else {

            $journalId = $request->query('journal_id');
            $journal = Journal::with('subjects')->find($journalId);

            if ($journalId && $journal) {
                Session::put('selected_journal', $journal->id);
                $data['action'] = 'update';
                $data['selectedJournal'] = $journal;
            } else {
                $selectedJournal = Session::get('selected_journal');
                if ($selectedJournal) {
                    $data['action'] = 'update';
                    $data['selectedJournal'] = Journal::with('subjects')->find($selectedJournal);
                }
            }

        }

        return view('user.submission.select-a-journal', $data);
    }

    public function select_a_journal_save(Request $request)
    {

        try {

            if ($request->has('id') && $request->id) {
                $clientOrder = ClientOrder::where('order_id', $request->id)->first();
                $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();
                $clientOrderSubmission->journal_id = $request->selected_journal;
                $clientOrderSubmission->save();

                return redirect()->route('user.submission.article.information', ['action' => 'update', 'id' => $clientOrder->order_id]);

                // $responseData = [
                //     "redirect_url" => route('user.submission.article.information', ['action' => 'update','id' => $clientOrder->order_id]),
                //     "action" => "step_one_save",
                //     "client_order_id" => $clientOrder->order_id,
                // ];

                // $this->success($responseData, []);

            } else {
                Session::put('selected_journal', $request->selected_journal);
                return redirect()->route('user.submission.article.information');
            }

        } catch (\Exception $e) {
            $this->error([], $e->getMessage());
        }
    }

    public function article_information($action = null, $id = null)
    {



        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';

        $data['step'] = "stepTwo";
        $data['articleTypes'] = ArticleType::where('status', 'active')->get();

        if (
            $action &&
            ($action == 'update') &&
            $id
        ) {

            $clientOrder = ClientOrder::where('order_id', $id)->first();
            $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();
            $data['selectedJournal'] = Journal::find($clientOrderSubmission->journal_id);

            $data['action'] = 'update';
            $data['clientOrderId'] = $id;
            $data['clientOrder'] = $clientOrder;
            $data['clientOrderSubmission'] = $clientOrderSubmission;
        } else {
            $selectedJournal = Session::get('selected_journal');
            $data['selectedJournal'] = Journal::find($selectedJournal);
        }
        return view('user.submission.article-information', $data);
    }

    public function article_information_save(Request $request)
    {

        // Validate form
        $request->validate([
            'article_type_id' => 'required|exists:article_types,id',
            'selected_journal_id' => 'required|exists:journals,id',
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'action' => 'required|string',
        ]);

        // Save data to the database
        // $article = new ArticleSubmission();
        // $article->article_type_id = $request->article_type_id;
        // $article->title = $request->title;
        // $article->abstract = $request->abstract;
        // $article->keywords = $request->keywords;
        // $article->save();

        try {

            if (
                $request->has('id') &&
                $request->id
            ) {


                $clientOrder = ClientOrder::where('order_id', $request->id)->first();
                $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();
                $clientOrderSubmission->article_type_id = $request->article_type_id;
                $clientOrderSubmission->article_title = $request->title;
                $clientOrderSubmission->article_abstract = $request->abstract;
                $clientOrderSubmission->article_keywords = $request->keywords;
                $clientOrderSubmission->language = session('local', 'ar'); // Update language on edit
                $clientOrderSubmission->save();



            } else {



                //Client order save
                $selectedJournal = Journal::where('id', $request->selected_journal_id)->first();
                $service = Service::where('id', $selectedJournal->service_id)->first();
                if (!$service) {
                    $this->error([], 'Service not found');
                }

                $userClient = User::where('id', auth()->user()->id)->first();
                if (!$userClient) {
                    $this->error([], 'User not found');
                }

                $userId = $service->user_id;
                $tenantId = $userClient->tenant_id;

                $amount = $service->price;
                $discount = 0;
                $discount_type = DISCOUNT_TYPE_FLAT;
                $platform_charge = 0.00;
                $order_create_type = 0;

                $orderItems = [
                    (object) [
                        'service_id' => $service->id,
                        'price' => $service->price,
                        'quantity' => 1,
                    ]
                ];

                $orderData = [
                    'amount' => $amount,
                    'discount' => $discount,
                    'discount_type' => $discount_type,
                    'platform_charge' => $platform_charge,
                    'order_create_type' => $order_create_type,
                    'orderItems' => (object) ($orderItems),
                    'recurring_type' => $service->recurring_type,
                    'recurring_payment_type' => $service->payment_type,
                ];
                $clientOrder = makeClientOrder($orderData, $userClient, $userId, $tenantId)['data'];

                // $file = new FileManager();
                // $uploaded = $file->upload('Service', $request->file);
                // $clientOrder->file = $uploaded->id;

                $clientOrder->save();


                //client order submission article information save
                $clientOrderSubmission = new ClientOrderSubmission();
                $clientOrderSubmission->client_order_id = $clientOrder->order_id;
                $clientOrderSubmission->journal_id = $request->selected_journal_id;
                $clientOrderSubmission->article_type_id = $request->article_type_id;
                $clientOrderSubmission->article_title = $request->title;
                $clientOrderSubmission->article_abstract = $request->abstract;
                $clientOrderSubmission->article_keywords = $request->keywords;
                $clientOrderSubmission->approval_status = SUBMISSION_ORDER_STATUS_INCOMPLETE;
                $clientOrderSubmission->language = session('local', 'ar'); // Detect from user's interface language
                $clientOrderSubmission->save();
            }

            Session::forget('selected_journal');

            // Check which button was clicked
            if ($request->action === 'save_and_continue') {


                $responseData = [
                    "redirect_url" => route('user.submission.upload.files', ['id' => $clientOrder->order_id])
                ];
                return $this->success($responseData, []);
            } else {

                $message = __(SAVED_SUCCESSFULLY);
                $responseData = [
                    "action" => "step_two_save",
                    "client_order_id" => $clientOrder->order_id,
                    //"reload" => true
                ];
                return $this->success($responseData, $message);
            }
        } catch (\Exception $e) {
            $this->error([], $e->getMessage());
        }

    }


    public function upload_files($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';
        $data['step'] = "stepThree";

        $clientOrder = ClientOrder::where('order_id', $id)->first();

        $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();

        $data['clientOrderId'] = $id;
        $data['clientOrder'] = $clientOrder;
        $data['clientOrderSubmission'] = $clientOrderSubmission;

        return view('user.submission.upload-files', $data);
    }

    public function upload_files_save(Request $request)
    {

        try {

            //save upload files data
            $clientOrder = ClientOrder::where('order_id', $request->id)->first();
            $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();


            //required upload files validation
            // $this->validate($request, [
            //     'full_article_file' => 'required',
            // 'supplementary_files' => 'max:5',
            // 'supplementary_files.*' => 'file|max:20480|mimes:doc,docx,pdf,...'
            // ]);

            //required file upload
            if ($request->hasFile('full_article_file')) {

                $existFile = FileManager::where('id', $clientOrderSubmission->full_article_file)->first();
                if ($existFile) {
                    $existFile->removeFile();
                    $uploadedFile = $existFile->upload('Order', $request->full_article_file, '', $existFile->id);
                    $clientOrderSubmission->full_article_file = $uploadedFile->id;
                } else {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('Order', $request->full_article_file);

                    $clientOrderSubmission->full_article_file = $uploadedFile->id;
                }
            }

            // Handle Cover Letter
            if ($request->hasFile('cover_letter_file')) {

                $existFile = FileManager::where('id', $clientOrderSubmission->covert_letter_file)->first();
                if ($existFile) {
                    $existFile->removeFile();
                    $uploadedFile = $existFile->upload('Order', $request->cover_letter_file, '', $existFile->id);
                    $clientOrderSubmission->covert_letter_file = $uploadedFile->id;
                } else {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('Order', $request->cover_letter_file);

                    $clientOrderSubmission->covert_letter_file = $uploadedFile->id;
                }
            }



            // Handle file storage
            if ($request->hasFile('supplementary_files')) {
                // Upload new files
                foreach ($request->file('supplementary_files') as $file) {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('Order', $file);

                    $supplymentMaterialFile = new SupplymentMaterialFile();
                    $supplymentMaterialFile->client_order_submission_id = $clientOrderSubmission->id;
                    $supplymentMaterialFile->file_id = $uploadedFile->id;
                    $supplymentMaterialFile->save();
                }
            }

            $clientOrderSubmission->save();

            // Check which button was clicked
            if ($request->action === 'save_and_continue') {


                $responseData = [
                    "redirect_url" => route('user.submission.add.authors', ['id' => $clientOrder->order_id])
                ];
                return $this->success($responseData, []);
            } else {

                $message = __(SAVED_SUCCESSFULLY);
                $responseData = [
                    //"action" => "step_two_save",
                    //"client_order_id" => $clientOrder->order_id,
                    //"reload" => true
                ];
                return $this->success($responseData, $message);
            }
            return $this->success($responseData, $message);
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->error([], $e->getMessage());
        }
    }

    public function add_authors($id)
    {
        $countries = config('countries');
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';
        $data['step'] = "stepFour";

        $contributorRoles = ContributorRole::where('status', 'active')->get();
        $clientOrder = ClientOrder::where('order_id', $id)->first();
        $clientOrderSubmission = ClientOrderSubmission::with('authors', 'authors_roles')->where('client_order_id', $clientOrder->order_id)->first();

        $data['clientOrderId'] = $id;
        $data['clientOrder'] = $clientOrder;
        $data['clientOrderSubmission'] = $clientOrderSubmission;
        $data['contributorRoles'] = $contributorRoles;
        $data['countries'] = $countries;

        $ip = request()->ip();
        //User Requested IP
        Log::info("User IP: " . $ip);

        $location = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
        $userCountry = $location && $location->status === 'success' ? $location->country : null;
        $data['userCountry'] = $userCountry;

        return view('user.submission.add-authors', $data);
    }

    public function add_authors_save(Request $request)
    {
        // dd($request->all());

        //  // Define validation rules
        //  $rules = [
        //     'authors' => 'required|array|min:1', // At least one author is required
        //     'authors.*.first_name' => 'required|string|max:255',
        //     'authors.*.last_name' => 'required|string|max:255',
        //     'authors.*.email' => 'required|email|max:255',
        //     'authors.*.orcid' => 'nullable|string|max:255',
        //     'authors.*.roles' => 'required|array|min:1', // At least one role is required
        //     'authors.*.roles.*' => 'required|string|max:255', // Each role must be a string
        //     'authors.*.corresponding_author' => 'required|in:0,1', // Must be either "0" or "1"
        //     'authors.*.affiliations' => 'required|array|min:1', // At least one affiliation is required
        //     'authors.*.affiliations.*' => 'required|string|max:255', // Each affiliation must be a string
        // ];

        // // Custom error messages (optional)
        // $messages = [
        //     'authors.*.first_name.required' => 'The first name field is required for all authors.',
        //     'authors.*.last_name.required' => 'The last name field is required for all authors.',
        //     'authors.*.email.required' => 'The email field is required for all authors.',
        //     'authors.*.roles.required' => 'At least one role is required for all authors.',
        //     'authors.*.affiliations.required' => 'At least one affiliation is required for all authors.',
        // ];
        // $this->validate($request,$rules,$messages);

        $request->validate([
            'authors' => ['required', 'array', 'min:1'],
            'authors.*.first_name' => ['required', 'string', 'max:100'],
            'authors.*.last_name' => ['required', 'string', 'max:100'],
            'authors.*.email' => ['required', 'email'],

            // Affiliation validation
            'authors.*.affiliations' => ['required', 'array', 'min:1'],
            // 'authors.*.affiliations.*' => [
            //     'nullable',
            //     'string',
            //     'min:30',
            //     'regex:/Department\s+of\s+[A-Za-z\s]+/i', // Department validation
            //     'regex:/Faculty\s+of\s+[A-Za-z\s]+/i',    // Faculty validation
            //     'regex:/(University|College|Institute)/i', // University/College/Institute validation
            //     'regex:/,[^,]+,[^,]+$/i', // City, Country validation
            // ],
        ], [
            // 'authors.*.affiliations.*.regex' => 'Each affiliation must be like: Department of ..., Faculty of ..., University, City, Country.',
            'authors.*.affiliations.*.min' => 'Each affiliation must be at least 30 characters.',
            'authors.*.affiliations.*.required' => 'Each affiliation field is required..',
        ]);

        try {

            //save upload files data
            $clientOrder = ClientOrder::where('order_id', $request->id)->first();
            $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();

            //store authors data
            $clientOrderSubmission->has_author = 1;
            AuthorDetails::where('client_order_submission_id', $clientOrderSubmission->id)->delete();
            AuthorContributorRole::where('client_order_submission_id', $clientOrderSubmission->id)->delete();
            foreach ($request->authors as $author) {
                //dd($author['affiliations']);
                $authorDetails = new AuthorDetails();
                $authorDetails->client_order_submission_id = $clientOrderSubmission->id;
                $authorDetails->first_name = $author['first_name'];
                $authorDetails->last_name = $author['last_name'];
                $authorDetails->email = $author['email'];
                $authorDetails->orcid = $author['orcid'];
                $authorDetails->affiliation = json_encode(array_filter($author['affiliations']));
                $authorDetails->corresponding_author = $author['corresponding_author'];
                $authorDetails->nationality = $author['nationality'];
                $authorDetails->whatsapp_number = $author['whatsapp'];
                $authorDetails->date_of_birth = $author['birthday'];
                $authorDetails->save();

                foreach ($author['roles'] as $role) {
                    AuthorContributorRole::create([
                        'client_order_submission_id' => $clientOrderSubmission->id,
                        'author_details_id' => $authorDetails->id,
                        'contributor_role_id' => $role
                    ]);
                }
            }
            $clientOrderSubmission->save();

            // Check which button was clicked
            if ($request->action === 'save_and_continue') {


                $responseData = [
                    "redirect_url" => route('user.submission.declarations', ['id' => $clientOrder->order_id])
                ];
                return $this->success($responseData, []);
            } else {

                $message = __(SAVED_SUCCESSFULLY);
                $responseData = [
                    //"action" => "step_two_save",
                    //"client_order_id" => $clientOrder->order_id,
                    //"reload" => true
                ];
                return $this->success($responseData, $message);
            }
            return $this->success($responseData, $message);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function declarations($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';
        $data['step'] = "stepFive";
        $clientOrder = ClientOrder::where('order_id', $id)->first();
        $clientOrderSubmission = ClientOrderSubmission::with('declarations', 'funders')->where('client_order_id', $clientOrder->order_id)->first();

        $data['clientOrderId'] = $id;
        $data['clientOrder'] = $clientOrder;
        $data['clientOrderSubmission'] = $clientOrderSubmission;
        $data['declarations'] = Declarations::where('status', 'active')->get();
        $data['service'] = Service::where('id', 1)->first();
        return view('user.submission.declarations', $data);
    }

    public function declarations_save(Request $request)
    {

        //dd($request->all());

        try {

            //save upload files data
            $clientOrder = ClientOrder::where('order_id', $request->id)->first();
            $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();

            ClientOrderSubmissionFundingDetails::where('client_order_submission_id', $clientOrderSubmission->id)->delete();
            ClientOrderSubmissionDeclarations::where('client_order_submission_id', $clientOrderSubmission->id)->delete();
            foreach ($request->declarations as $declaration) {
                if (is_array($declaration)) {
                    // Extract the first value if it's an array
                    $declaration = reset($declaration);
                }
               // dd($declaration);
                $clientOrderSubmissionDeclarations = new ClientOrderSubmissionDeclarations();
                $clientOrderSubmissionDeclarations->client_order_submission_id = $clientOrderSubmission->id;
                $clientOrderSubmissionDeclarations->declaration_id = $declaration;
                $clientOrderSubmissionDeclarations->accepted = 1;
                $clientOrderSubmissionDeclarations->save();
            }
            if ($request->conflict_interest == 1) {
                $clientOrderSubmission->has_conflict_of_interest = true;
                $clientOrderSubmission->conflict_details = $request->conflict_description;
            } else {
                $clientOrderSubmission->has_conflict_of_interest = false;
                $clientOrderSubmission->conflict_details = null;
            }

            if ($request->funding_received == 1) {
                $clientOrderSubmission->has_funding = true;
                ClientOrderSubmissionFundingDetails::where('client_order_submission_id', $clientOrderSubmission->id)->delete();
                foreach ($request->funderInfo as $info) {
                    $clientOrderSubmissionFunder = new ClientOrderSubmissionFundingDetails();
                    $clientOrderSubmissionFunder->client_order_submission_id = $clientOrderSubmission->id;
                    $clientOrderSubmissionFunder->funder = $info['funder'];
                    $clientOrderSubmissionFunder->grant_number = $info['grant'] ? $info['grant'] : null;
                    $clientOrderSubmissionFunder->save();
                }
            } else {
                ClientOrderSubmissionFundingDetails::where('client_order_submission_id', $clientOrderSubmission->id)->delete();
                $clientOrderSubmission->has_funding = false;
            }

            if ($request->has('data_availability')) {
                $clientOrderSubmission->has_data_availability_statement = true;
                $clientOrderSubmission->data_availability_statement = $request->data_availability;
                if ($request->data_availability == "dataAvailabilityOne") {
                    $clientOrderSubmission->data_availability_url = $request->data_repository_url;
                } else {
                    $clientOrderSubmission->data_availability_url = null;
                }
            } else {
                $clientOrderSubmission->has_data_availability_statement = false;
            }


            $clientOrderSubmission->save();

            // Check which button was clicked
            if ($request->action === 'save_and_continue') {
                $responseData = [
                    "redirect_url" => route('user.submission.add-reviewers.index', ['id' => $clientOrder->order_id])
                ];
                return $this->success($responseData, []);
            } else {

                $message = __(SAVED_SUCCESSFULLY);
                $responseData = [
                    //"action" => "step_two_save",
                    //"client_order_id" => $clientOrder->order_id,
                    //"reload" => true
                ];
                return $this->success($responseData, $message);
            }
            return $this->success($responseData, $message);
        } catch (\Exception $e) {
            $this->error([], $e->getMessage());
        }
    }

    public function add_reviewers_index($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';

        $data['step'] = "stepSix";
        $clientOrder = ClientOrder::where('order_id', $id)->first();
        $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();

        $data['clientOrderId'] = $id;
        $data['clientOrder'] = $clientOrder;
        $data['clientOrderSubmission'] = $clientOrderSubmission;
        return view('user.submission.add-reviewers.index', $data);
    }

    public function add_reviewers_save(Request $request)
    {
        try {

            //save upload files data
            $clientOrder = ClientOrder::where('order_id', $request->id)->first();
            $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();
            $clientOrderSubmission->add_reviewers = true;
            $clientOrderSubmission->save();

            // Check which button was clicked
            if ($request->action === 'save_and_continue') {
                $responseData = [
                    "redirect_url" => route('user.submission.add-reviewers.from-references', ['id' => $clientOrder->order_id])
                ];
                return $this->success($responseData, []);
            } else {

                $message = __(SAVED_SUCCESSFULLY);
                $responseData = [
                    //"action" => "step_two_save",
                    //"client_order_id" => $clientOrder->order_id,
                    //"reload" => true
                ];
                return $this->success($responseData, $message);
            }
            return $this->success($responseData, $message);
        } catch (\Exception $e) {
            $this->error([], $e->getMessage());
        }
    }

    public function add_reviewers_from_references($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';

        $data['step'] = "stepSixSubOne";
        $clientOrder = ClientOrder::where('order_id', $id)->first();
        $clientOrderSubmission = ClientOrderSubmission::with('__suggested_reviewers')->where('client_order_id', $clientOrder->order_id)->first();

        $data['clientOrderId'] = $id;
        $data['clientOrder'] = $clientOrder;
        $data['clientOrderSubmission'] = $clientOrderSubmission;
        return view('user.submission.add-reviewers.from-references', $data);
    }

    public function add_reviewers_from_references_save(Request $request)
    {

        try {

            //save upload files data
            $clientOrder = ClientOrder::where('order_id', $request->id)->first();
            $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();


            $suggestedReviewers = $request->input('suggested_reviewers', []);
            // Filter out completely empty entries
            $filteredReviewers = array_filter($suggestedReviewers, function ($reviewer) {
                return !empty(array_filter($reviewer, function ($value) {
                    return $value !== null;
                }));
            });

            // Determine if we have any valid reviewers
            $hasSuggestedReviewers = !empty($filteredReviewers);

            ClientOrderSubmissionReviewers::where('client_order_submission_id', $clientOrderSubmission->id)->delete();
            if ($hasSuggestedReviewers) {
                $clientOrderSubmission->suggested_reviewers = true;
                foreach ($filteredReviewers as $reviewer) {
                    $clientOrderSubmissionReviewers = new ClientOrderSubmissionReviewers();
                    $clientOrderSubmissionReviewers->client_order_submission_id = $clientOrderSubmission->id;
                    $clientOrderSubmissionReviewers->referred_article_title = $reviewer['referred_article_title'];
                    $clientOrderSubmissionReviewers->corresponding_author_first_name = $reviewer['corresponding_author_first_name'];
                    $clientOrderSubmissionReviewers->corresponding_author_last_name = $reviewer['corresponding_author_last_name'];
                    $clientOrderSubmissionReviewers->corresponding_author_email = $reviewer['corresponding_author_email'];
                    $clientOrderSubmissionReviewers->first_author_first_name = $reviewer['first_author_first_name'];
                    $clientOrderSubmissionReviewers->first_author_last_name = $reviewer['first_author_last_name'];
                    $clientOrderSubmissionReviewers->first_author_email = $reviewer['first_author_email'];
                    $clientOrderSubmissionReviewers->save();
                }
            } else {
                $clientOrderSubmission->suggested_reviewers = false;
            }

            $clientOrderSubmission->save();

            // Check which button was clicked
            if ($request->action === 'save_and_continue') {
                $responseData = [
                    "redirect_url" => route('user.submission.add-reviewers.opposed', ['id' => $clientOrder->order_id])
                ];
                return $this->success($responseData, []);
            } else {

                $message = __(SAVED_SUCCESSFULLY);
                $responseData = [
                    //"action" => "step_two_save",
                    //"client_order_id" => $clientOrder->order_id,
                    //"reload" => true
                ];
                return $this->success($responseData, $message);
            }
            return $this->success($responseData, $message);
        } catch (\Exception $e) {
            $this->error([], $e->getMessage());
        }
    }

    public function add_reviewers_opposed($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';

        $data['step'] = "stepSixSubTwo";
        $clientOrder = ClientOrder::where('order_id', $id)->first();
        $clientOrderSubmission = ClientOrderSubmission::with('__opposed_reviewers')->where('client_order_id', $clientOrder->order_id)->first();

        $data['clientOrderId'] = $id;
        $data['clientOrder'] = $clientOrder;
        $data['clientOrderSubmission'] = $clientOrderSubmission;
        return view('user.submission.add-reviewers.opposed', $data);
    }

    public function add_reviewers_opposed_save(Request $request)
    {
        try {

            $opposedReviewersRadio = $request->input('opposed_reviewers_radio');
            $opposedReviewers = $request->input('opposed_reviewers', []);
            // Filter out completely empty entries
            $filteredReviewers = array_filter($opposedReviewers, function ($reviewer) {
                return !empty(array_filter($reviewer, function ($value) {
                    return $value !== null;
                }));
            });

            // Determine if we have any valid reviewers
            $hasOpposedReviewers = !empty($filteredReviewers);

            //save upload files data
            $clientOrder = ClientOrder::where('order_id', $request->id)->first();
            $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();

            ClientOrderSubmissionOpposedReviewers::where('client_order_submission_id', $clientOrderSubmission->id)->delete();

            if ($opposedReviewersRadio && $hasOpposedReviewers) {
                ClientOrderSubmissionOpposedReviewers::where('client_order_submission_id', $clientOrderSubmission->id)->delete();
                $clientOrderSubmission->has_opposed_reviewers = true;
                foreach ($filteredReviewers as $opposed_reviewer) {
                    $clientOrderSubmissionOpposedReviewers = new ClientOrderSubmissionOpposedReviewers();
                    $clientOrderSubmissionOpposedReviewers->client_order_submission_id = $clientOrderSubmission->id;
                    $clientOrderSubmissionOpposedReviewers->first_name = $opposed_reviewer['first_name'];
                    $clientOrderSubmissionOpposedReviewers->last_name = $opposed_reviewer['last_name'];
                    $clientOrderSubmissionOpposedReviewers->email = $opposed_reviewer['email'];
                    $clientOrderSubmissionOpposedReviewers->affiliation = $opposed_reviewer['affiliation'];
                    $clientOrderSubmissionOpposedReviewers->save();
                }
            } else {
                ClientOrderSubmissionOpposedReviewers::where('client_order_submission_id', $clientOrderSubmission->id)->delete();
                $clientOrderSubmission->has_opposed_reviewers = false;
            }
            $clientOrderSubmission->save();


            // Check which button was clicked
            if ($request->action === 'save_and_continue') {
                $responseData = [
                    "redirect_url" => route('user.submission.review', ['id' => $clientOrder->order_id])
                ];
                return $this->success($responseData, []);
            } else {

                $message = __(SAVED_SUCCESSFULLY);
                $responseData = [
                    //"action" => "step_two_save",
                    //"client_order_id" => $clientOrder->order_id,
                    //"reload" => true
                ];
                return $this->success($responseData, $message);
            }
            return $this->success($responseData, $message);
        } catch (\Exception $e) {
            $this->error([], $e->getMessage());
        }
    }

    public function review($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';

        $data['step'] = "stepSeven";

        $clientOrder = ClientOrder::where('order_id', $id)->first();
        $clientOrderSubmission = ClientOrderSubmission::with('journal', 'article_type', 'supplyment_material_files', 'authors', 'authors_roles', 'funders', '__suggested_reviewers')->where('client_order_id', $clientOrder->order_id)->first();

        $data['clientOrderId'] = $id;
        $data['clientOrder'] = $clientOrder;
        $data['clientOrderSubmission'] = $clientOrderSubmission;
        return view('user.submission.review', $data);
    }

    public function review_save(Request $request)
    {
        try {

            //save upload files data
            $clientOrder = ClientOrder::with('client')->where('order_id', $request->id)->first();
            $clientOrderSubmission = ClientOrderSubmission::with('journal')->where('client_order_id', $clientOrder->order_id)->first();
            $finalSubmissionSuccessed = $clientOrderSubmission->final_submit_success;
            $clientOrderSubmission->final_submit_success = 1;
            $clientOrderSubmission->approval_status = SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW;
            $clientOrderSubmission->save();

            if (!$finalSubmissionSuccessed) {

                articleSubmissionEmailNotify($clientOrder, $clientOrderSubmission, $clientOrder->client);
                SubmissionOrderCreatedNotifyForAuthor($clientOrder, $clientOrder->client);

                //admin mail sent
                $admins = User::whereIn('role', [USER_ROLE_ADMIN])->get();
                foreach ($admins as $admin) {
                    articleSubmissionEmailNotify($clientOrder, $clientOrderSubmission, $admin);
                    SubmissionOrderCreatedNotifyForAdmin($clientOrder, $admin);
                    Log::debug("Admin: " . $admin->email);
                }

            }

            // // Check which button was clicked
            // if ($request->action === 'save_and_continue') {
                //dd(route('user.submission.success',['id' => $clientOrder->order_id]));
                $responseData = [
                "redirect_url" => route('user.submission.success.review', ['id' => $clientOrder->order_id])
                ];
                return $this->success($responseData, []);
            // };

        } catch (\Exception $e) {
            //dd($e);
            $this->error([], $e->getMessage());
        }
    }

    public function revisionForm($orderId)
    {
        $clientOrder = ClientOrder::where('order_id', $orderId)
            ->where('client_id', auth()->id())
            ->with([
                'client_order_submission.revisions.attachments.file',
                'client_order_submission.revisions.manuscriptFile',
                'client_order_submission.revisions.responseFile',
            ])
            ->firstOrFail();

        $submission = $clientOrder->client_order_submission;

        if (!$submission) {
            abort(404);
        }

        $reviews = Reviews::where('client_order_submission_id', $submission->id)
            ->with('reviewer:id,name')
            ->orderByDesc('updated_at')
            ->get();

        $revisions = $submission->revisions->sortByDesc('version')->values();
        $nextVersion = ($revisions->max('version') ?? 0) + 1;

        $data = [
            'pageTitleParent' => __('Submission'),
            'pageTitle' => __('Submit Revision'),
            'activeOrder' => 'active',
            'clientOrder' => $clientOrder,
            'submission' => $submission,
            'reviews' => $reviews,
            'revisions' => $revisions,
            'nextVersion' => $nextVersion,
            'statusLabel' => ucwords(str_replace('_', ' ', $submission->approval_status)),
        ];

        return view('user.submission.revision', $data);
    }

    public function revisionSubmit(Request $request, $orderId)
    {

        $clientOrder = ClientOrder::where('order_id', $orderId)
            ->where('client_id', auth()->id())
            ->with('client_order_submission.revisions')
            ->firstOrFail();

        $submission = $clientOrder->client_order_submission;


        if (!$submission) {
            abort(404);
        }

        $request->validate([
            'manuscript_file' => 'required|file|max:51200',
            'response_summary' => 'nullable|string',
            'general_response' => 'nullable|string',
            'response_file' => 'nullable|file|max:51200',
            'attachments.*' => 'nullable|file|max:51200',
        ]);

        // Collect all reviewer-specific responses
        $reviewerResponses = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'response_to_reviewer_') === 0) {
                $reviewerId = str_replace('response_to_reviewer_', '', $key);
                if (!empty($value)) {
                    $reviewerResponses[$reviewerId] = $value;
                }
            }
        }

        DB::beginTransaction();

        try {
            $nextVersion = ($submission->revisions->max('version') ?? 0) + 1;

            $manuscriptUploader = new FileManager();
            $manuscript = $manuscriptUploader->upload('Revision', $request->file('manuscript_file'), null, null, 'revision');

            if (!$manuscript) {
                throw new \RuntimeException('Failed to store manuscript file.');
            }

            $responseFileId = null;

            if ($request->hasFile('response_file')) {
                $responseUploader = new FileManager();
                $responseFile = $responseUploader->upload('Revision', $request->file('response_file'));
                if (!$responseFile) {
                    throw new \RuntimeException('Failed to store response document.');
                }
                $responseFileId = $responseFile->id;
            }

            $revision = ClientOrderSubmissionRevision::create([
                'client_order_submission_id' => $submission->id,
                'client_order_id' => $clientOrder->order_id,
                'author_id' => auth()->id(),
                'version' => $nextVersion,
                'manuscript_file_id' => $manuscript->id,
                'response_file_id' => $responseFileId,
                'response_summary' => $request->input('response_summary'),
                'metadata' => [
                    'submitted_via' => 'portal',
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'attachments' => $request->hasFile('attachments') ? count($request->file('attachments')) : 0,
                    'general_response' => $request->input('general_response'),
                    'reviewer_responses' => $reviewerResponses,
                ],
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $index => $attachment) {
                    if (!$attachment) {
                        continue;
                    }

                    $attachmentUploader = new FileManager();
                    $uploadedAttachment = $attachmentUploader->upload('Revision', $attachment);

                    if ($uploadedAttachment) {
                        ClientOrderSubmissionRevisionFile::create([
                            'revision_id' => $revision->id,
                            'file_id' => $uploadedAttachment->id,
                            'label' => __('Attachment :number', ['number' => $index + 1]),
                        ]);
                    }
                }
            }

            $submission->approval_status = SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW;
            $submission->save();

            DB::commit();

            return redirect()
                ->route('user.orders.dashboard', ['order_id' => $orderId])
                ->with('success', __('Revision submitted successfully. The editorial team will notify you once the review is complete.'));
        } catch (\Throwable $throwable) {
            DB::rollBack();

            Log::error('revision_submit_failed', [
                'order_id' => $orderId,
                'user_id' => auth()->id(),
                'message' => $throwable->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withErrors(['revision' => __('Unable to submit your revision right now. Please try again or contact support.')])
                ->withInput();
        }
    }

    public function success_review($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Submission Order');
        $data['activeOrder'] = 'active';
        $data['step'] = "success";

        $clientOrder = ClientOrder::where('order_id', $id)->first();

        $clientOrderSubmission = ClientOrderSubmission::where('client_order_id', $clientOrder->order_id)->first();

        $data['clientOrderId'] = $id;
        $data['clientOrder'] = $clientOrder;
        $data['clientOrderSubmission'] = $clientOrderSubmission;
        return view('user.submission.success', $data);
    }

    public function getJournalsBySubject(Request $request)
    {

        $subject = JournalSubject::with([
            'journals' => function ($q) {
            $q->with('service')->where('status', 'active');
            }
        ])->findOrFail($request->subject_id);

        $journals = $subject->journals;


        return response()->json([
            'journals' => $journals
        ]);
    }

    public function getJournalsByLetter(Request $request)
    {
        $journals = Journal::where('title', 'like', $request->letter . '%')
        ->with('service')
        ->where('status', 'active')
        ->get();
        return response()->json([
            'journals' => $journals,
        ]);
    }

    public function searchJournals(Request $request)
    {
        $query = $request->query_data;
        $journals = Journal::where('title', 'like', '%' . $query . '%')
            ->with('service')
            ->where('status', 'active')
            ->get();

        return response()->json([
            'journals' => $journals,
        ]);
    }

    public function deleteCoverLetter(Request $request)
    {

        try {
            $existFile = FileManager::where('id', $request->file_id)->first();
            if ($existFile) {
                $existFile->removeFile();
                $clientOrderSubmission = ClientOrderSubmission::where('covert_letter_file', $request->file_id)->first();
                $clientOrderSubmission->covert_letter_file = null;
                $clientOrderSubmission->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } catch (\Exception $e) {
            $this->error([], $e->getMessage());
        }
    }

    public function deleteSupplementary(Request $request)
    {
        try {
            $existFile = FileManager::where('id', $request->file_id)->first();
            if ($existFile) {
                $existFile->removeFile();
                SupplymentMaterialFile::where('file_id', $request->file_id)->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } catch (\Exception $e) {
            $this->error([], $e->getMessage());
        }
    }

    public function authorDashboard(string $orderId)
    {
        $data = $this->buildAuthorSubmissionData($orderId);

        // Add workflow tracker data
        $submission = $data['submission'] ?? null;
        $order = $data['order'] ?? null;
        $invoice = $data['invoice'] ?? null;
        $data['workflowTracker'] = $this->buildWorkflowTracker($submission, $order, $invoice);

        return view('user.submission.author-dashboard', $data);
    }

    public function authorReviewsSummary(string $orderId)
    {
        $data = $this->buildAuthorSubmissionData($orderId);

        $data['feedbackPending'] = $data['completedReviews']->isEmpty();

        return view('user.submission.author-review-feedback', $data);
    }

    protected function buildAuthorSubmissionData(string $orderId): array
    {
        $order = ClientOrder::with([
                'client',
                'client_order_items',
                'client_order_submission.journal',
                'client_order_submission.article_type',
                'client_order_submission.authors',
                'client_order_submission.supplyment_material_files',
                'client_order_submission.funders',
                'client_order_submission.declarations',
                'client_order_submission.__suggested_reviewers',
            'client_order_submission.proofFiles.file',
            'client_order_submission.proofFiles.uploadedBy',
            'client_order_submission.galleyFiles.file',
            'client_order_submission.galleyFiles.uploadedBy',
            ])
            ->where('order_id', $orderId)
            ->where('client_id', auth()->id())
            ->firstOrFail();

        $submission = $order->client_order_submission;

        if (!$submission) {
            abort(404);
        }

        // Fetch reviews linked by submission_id, or fallback to order_id
        $reviews = Reviews::with('reviewer:id,name')
            ->where(function ($query) use ($submission, $order) {
                $query->where('client_order_submission_id', $submission->id)
                    ->orWhere('client_order_id', $order->order_id);
            })
            ->orderBy('created_at')
            ->get();

        // Consider a review "completed" if:
        // 1. Status is explicitly completed, OR
        // 2. Has submitted_at timestamp, OR
        // 3. Has actual feedback data (recommendation + at least one rating or comment)
        $completedReviews = $reviews->filter(function (Reviews $review) {
            if ($review->status === SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED) {
                return true;
            }

            if ($review->submitted_at) {
                return true;
            }

            // Check if review has substantial feedback
            $hasRecommendation = !empty($review->overall_recommendation);
            $hasRating = !is_null($review->rating_originality)
                || !is_null($review->rating_methodology)
                || !is_null($review->rating_results)
                || !is_null($review->rating_clarity)
                || !is_null($review->rating_significance);
            $hasComment = !empty($review->comment_for_authors)
                || !empty($review->comment_strengths)
                || !empty($review->comment_weaknesses);

            return $hasRecommendation && ($hasRating || $hasComment);
        })->map(function (Reviews $review) {
            // Ensure round is set (default to 1 if null)
            if (is_null($review->round) || $review->round == 0) {
                $review->round = 1;
            }
            // Ensure version is set (default to 1 if null)
            if (is_null($review->version) || $review->version == 0) {
                $review->version = 1;
            }
            return $review;
        });

        $assignments = ClientOrderAssignee::with(['reviewer:id,name', 'assigner:id,name'])
            ->where('order_id', $order->id)
            ->get();

        $invoice = ClientInvoice::where('order_id', $order->id)->latest()->first();

        $progress = $this->buildAuthorProgress($submission->approval_status ?? SUBMISSION_ORDER_STATUS_PENDING);
        $statusMeta = $this->describeSubmissionStatus($submission->approval_status ?? SUBMISSION_ORDER_STATUS_PENDING, $reviews);
        $timeline = $this->buildAuthorTimeline($order, $submission, $assignments, $reviews, $invoice);

        $authors = $submission->authors->map(function (AuthorDetails $author) {
            // Handle affiliation - it may be array (from cast) or JSON string
            $affiliationData = $author->affiliation ?? [];
            if (is_string($affiliationData)) {
                $affiliationData = json_decode($affiliationData, true) ?? [];
            }
            if (!is_array($affiliationData)) {
                $affiliationData = [];
            }

            $affiliation = collect($affiliationData)
                ->flatten()
                ->filter()
                ->implode(', ');

            return [
                'name' => trim($author->first_name . ' ' . $author->last_name),
                'email' => $author->email,
                'affiliation' => $affiliation ?: null,
                'corresponding' => (bool) $author->corresponding_author,
            ];
        });

        $correspondingAuthor = $authors->firstWhere('corresponding', true) ?? $authors->first();

        $keywords = collect(explode(',', (string) $submission->article_keywords))
            ->map(fn($keyword) => trim($keyword))
            ->filter();

        $files = [
            'manuscript' => $submission->full_article_file && function_exists('getFileUrl')
                ? getFileUrl($submission->full_article_file)
                : null,
            'cover_letter' => $submission->covert_letter_file && function_exists('getFileUrl')
                ? getFileUrl($submission->covert_letter_file)
                : null,
            'supplements' => ($submission->supplyment_material_files ?? collect())
                ->map(function ($file) {
                    if (!function_exists('getFileUrl')) {
                        return null;
                    }

                    return [
                        'id' => $file->file_id,
                        'url' => getFileUrl($file->file_id),
                    ];
                })
                ->filter(),
        ];

        $reviewStats = [
            'assigned_reviewers' => max($assignments->count(), $reviews->count()),
            'completed_reviews' => $completedReviews->count(),
            'pending_reviews' => max(0, $reviews->count() - $completedReviews->count()),
            'average_rating' => $completedReviews->avg('quality_rating'),
            'last_submitted_at' => $completedReviews->max(fn(Reviews $review) => $review->submitted_at ?? $review->updated_at),
            'pending_invitations' => $assignments->where('invitation_status', 'pending')->count(),
        ];

        $dashboardMeta = [
            'days_in_review' => $submission->created_at
                ? Carbon::parse($submission->created_at)->diffInDays(Carbon::now())
                : null,
            'expected_decision' => $this->resolveExpectedDecisionDate($submission, $assignments),
            'language' => $submission->language ?? 'ar',
            'payment_status' => $this->formatPaymentStatus($order->payment_status),
            'payment_is_paid' => $order->payment_status === PAYMENT_STATUS_PAID,
            'invoice' => $invoice,
            'has_feedback' => $completedReviews->isNotEmpty(),
        ];

        // Load all author revisions grouped by round
        $revisionsByRound = collect();
        if ($submission) {
            $allRevisions = ClientOrderSubmissionRevision::with(['attachments.file'])
                ->where('client_order_submission_id', $submission->id)
                ->orderByRaw('COALESCE(version, 1)')
                ->orderBy('created_at')
                ->get()
                ->map(function ($revision) {
                    if (function_exists('getFileUrl')) {
                        $revision->manuscript_url = $revision->manuscript_file_id
                            ? getFileUrl($revision->manuscript_file_id)
                            : null;
                        $revision->response_url = $revision->response_file_id
                            ? getFileUrl($revision->response_file_id)
                            : null;
                    } else {
                        $revision->manuscript_url = null;
                        $revision->response_url = null;
                    }

                    $revision->attachment_links = $revision->attachments
                        ? $revision->attachments->map(function ($attachment) {
                            return [
                                'label' => $attachment->label ?? __('Attachment'),
                                'url' => function_exists('getFileUrl') ? getFileUrl($attachment->file_id) : null,
                            ];
                        })
                        : collect();

                    return $revision;
                });

            // Group revisions by version (which represents the round)
            $revisionsByRound = $allRevisions->groupBy(function ($revision) {
                return $revision->version ?? 1;
            });
        }

        return [
            'order' => $order,
            'submission' => $submission,
            'reviews' => $reviews,
            'completedReviews' => $completedReviews,
            'assignments' => $assignments,
            'progress' => $progress,
            'statusMeta' => $statusMeta,
            'timeline' => $timeline,
            'authors' => $authors,
            'correspondingAuthor' => $correspondingAuthor,
            'keywords' => $keywords,
            'files' => $files,
            'reviewStats' => $reviewStats,
            'dashboardMeta' => $dashboardMeta,
            'checklistLabels' => $this->reviewChecklistLabels(),
            'revisionsByRound' => $revisionsByRound,
        ];
    }

    protected function buildAuthorProgress(string $status): array
    {
        $steps = collect([
            ['key' => 'submitted', 'label' => __('Submitted')],
            ['key' => 'initial_check', 'label' => __('Initial Check')],
            ['key' => 'editor_assigned', 'label' => __('Editor Assigned')],
            ['key' => 'peer_review', 'label' => __('Peer Review')],
            ['key' => 'decision', 'label' => __('Decision')],
            ['key' => 'publication', 'label' => __('Publication')],
        ]);

        $statusToStep = [
            SUBMISSION_ORDER_STATUS_PENDING => 0,
            SUBMISSION_ORDER_STATUS_INCOMPLETE => 0,
            SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW => 1,
            SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED => 2,
            SUBMISSION_ORDER_STATUS_PENDING_PAYMENT => 2,
            SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED => 3,
            SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW => 3,
            SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS => 4,
            SUBMISSION_ORDER_STATUS_ACCEPTED => 4,
            SUBMISSION_ORDER_STATUS_PEER_REJECTED => 4,
            SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION => 5,
            SUBMISSION_ORDER_STATUS_PUBLISHED => 5,
        ];

        $currentIndex = $statusToStep[$status] ?? 0;
        $maxIndex = max($steps->count() - 1, 1);

        $steps = $steps->values()->map(function (array $step, int $index) use ($currentIndex) {
            if ($index < $currentIndex) {
                $step['state'] = 'complete';
            } elseif ($index === $currentIndex) {
                $step['state'] = 'active';
            } else {
                $step['state'] = 'upcoming';
            }
            $step['position'] = $index + 1;

            return $step;
        });

        $percentage = (int) round(($currentIndex / $maxIndex) * 100);

        return [
            'steps' => $steps,
            'percentage' => min(100, max(0, $percentage)),
        ];
    }

    protected function buildWorkflowTracker($submission, $order, $invoice): array
    {
        $steps = [
            ['number' => 1, 'label' => __('Submitted'), 'key' => 'submitted'],
            ['number' => 2, 'label' => __('Under Primary Review'), 'key' => 'primary_review'],
            ['number' => 3, 'label' => __('Under Peer Review'), 'key' => 'peer_review'],
            ['number' => 4, 'label' => __('Decision'), 'key' => 'decision'],
            ['number' => 5, 'label' => __('Accepted'), 'key' => 'accepted'],
            ['number' => 6, 'label' => __('Payment Due'), 'key' => 'payment_due'],
            ['number' => 7, 'label' => __('Published'), 'key' => 'published'],
        ];

        $status = $submission->approval_status ?? SUBMISSION_ORDER_STATUS_PENDING;
        $orderPaymentStatus = $order->payment_status ?? null;
        $invoicePaymentStatus = $invoice ? ($invoice->payment_status ?? null) : null;
        $isPaymentPending = ($orderPaymentStatus == PAYMENT_STATUS_PENDING || $orderPaymentStatus == null) &&
                            ($invoicePaymentStatus == PAYMENT_STATUS_PENDING || $invoicePaymentStatus == null);

        // Determine current step based on status
        $currentStep = 1; // Default to Submitted

        // Map status to workflow step
        if (in_array($status, [SUBMISSION_ORDER_STATUS_PENDING, SUBMISSION_ORDER_STATUS_INCOMPLETE])) {
            $currentStep = 1; // Submitted
        } elseif ($status === SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW) {
            $currentStep = 2; // Under Primary Review
        } elseif ($status === SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW) {
            $currentStep = 3; // Under Peer Review
        } elseif ($status === SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED) {
            // Initial acceptance - paper moves to peer review stage
            $currentStep = 3; // Under Peer Review
        } elseif (in_array($status, [
            SUBMISSION_ORDER_STATUS_ACCEPTED,
            SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS,
            SUBMISSION_ORDER_STATUS_PEER_REJECTED,
            SUBMISSION_ORDER_STATUS_INITIAL_REJECTED
        ])) {
            $currentStep = 4; // Decision
        } elseif ($status === SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION) {
            // Check if payment is pending
            if ($isPaymentPending || $status === SUBMISSION_ORDER_STATUS_PENDING_PAYMENT) {
                $currentStep = 6; // Payment Due
            } else {
                $currentStep = 5; // Accepted (payment done)
            }
        } elseif ($status === SUBMISSION_ORDER_STATUS_PENDING_PAYMENT) {
            $currentStep = 6; // Payment Due
        } elseif ($status === SUBMISSION_ORDER_STATUS_PUBLISHED) {
            $currentStep = 7; // Published
        } elseif ($status === SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED) {
            // Payment confirmed - move to accepted or next stage
            $currentStep = 5; // Accepted
        }

        // Build step states
        $steps = collect($steps)->map(function ($step, $index) use ($currentStep) {
            $stepNumber = $index + 1;
            if ($stepNumber < $currentStep) {
                $step['state'] = 'completed';
            } elseif ($stepNumber === $currentStep) {
                $step['state'] = 'active';
            } else {
                $step['state'] = 'future';
            }
            return $step;
        })->toArray();

        return [
            'steps' => $steps,
            'currentStep' => $currentStep,
        ];
    }

    protected function describeSubmissionStatus(string $status, Collection $reviews): array
    {
        $statusLabel = $this->formatSubmissionStatus($status);
        $badgeClass = 'status-under-review';
        $icon = '🔍';
        $headline = $statusLabel;
        $body = __('Your manuscript is moving through the editorial workflow. You will be notified as soon as a new decision is available.');

        $statusDescriptions = [
            SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW => [
                'badge' => 'status-initial-check',
                'icon' => '📝',
                'headline' => __('Initial Screening in Progress'),
                'body' => __('The editorial office is checking formatting, compliance, and completeness before assigning reviewers.'),
            ],
            SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED => [
                'badge' => 'status-editor-assigned',
                'icon' => '✅',
                'headline' => __('Initial Review Passed'),
                'body' => __('Your manuscript cleared the internal screening and is being prepared for peer review.'),
            ],
            SUBMISSION_ORDER_STATUS_PENDING_PAYMENT => [
                'badge' => 'status-payment',
                'icon' => '💳',
                'headline' => __('Awaiting Payment'),
                'body' => __('Please complete the payment to move your manuscript into the peer review queue.'),
            ],
            SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED => [
                'badge' => 'status-payment',
                'icon' => '💳',
                'headline' => __('Payment Confirmed'),
                'body' => __('Thank you! Your payment was received and the manuscript is on its way to peer review.'),
            ],
            SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW => [
                'badge' => 'status-under-review',
                'icon' => '🔬',
                'headline' => __('Peer Review in Progress'),
                'body' => __('Reviewers are evaluating your manuscript. You will be notified once all reports are submitted.'),
            ],
            SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS => [
                'badge' => 'status-revision-required',
                'icon' => '🛠️',
                'headline' => __('Revisions Requested'),
                'body' => __('Please review the feedback and prepare a revised manuscript along with a detailed response letter.'),
            ],
            SUBMISSION_ORDER_STATUS_ACCEPTED => [
                'badge' => 'status-accepted',
                'icon' => '🎉',
                'headline' => __('Manuscript Accepted'),
                'body' => __('Congratulations! Your manuscript has been accepted. We will guide you through the remaining publication steps.'),
            ],
            SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION => [
                'badge' => 'status-accepted',
                'icon' => '📅',
                'headline' => __('Queued for Publication'),
                'body' => __('Your manuscript is scheduled for publication. The production team will be in touch for proofing.'),
            ],
            SUBMISSION_ORDER_STATUS_PUBLISHED => [
                'badge' => 'status-published',
                'icon' => '📢',
                'headline' => __('Published'),
                'body' => __('Your manuscript is now live. Thank you for publishing with us!'),
            ],
            SUBMISSION_ORDER_STATUS_INITIAL_REJECTED => [
                'badge' => 'status-rejected',
                'icon' => '⚠️',
                'headline' => __('Initial Review Outcome'),
                'body' => __('We regret to inform you that the manuscript could not pass the initial evaluation. Please review the editorial notes to consider resubmission.'),
            ],
            SUBMISSION_ORDER_STATUS_PEER_REJECTED => [
                'badge' => 'status-rejected',
                'icon' => '⚠️',
                'headline' => __('Peer Review Outcome'),
                'body' => __('The manuscript was not recommended for publication. Please review the feedback to decide on your next steps.'),
            ],
        ];

        if (isset($statusDescriptions[$status])) {
            $badgeClass = $statusDescriptions[$status]['badge'];
            $icon = $statusDescriptions[$status]['icon'];
            $headline = $statusDescriptions[$status]['headline'];
            $body = $statusDescriptions[$status]['body'];
        }

        $reviewsReceived = $reviews->filter(fn(Reviews $review) => $review->status === SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED)->count();
        $totalReviews = max($reviews->count(), $reviewsReceived);

        return [
            'label' => $statusLabel,
            'badge_class' => $badgeClass,
            'icon' => $icon,
            'headline' => $headline,
            'body' => $body,
            'reviews_received' => $reviewsReceived,
            'total_reviews' => $totalReviews,
        ];
    }

    protected function buildAuthorTimeline(ClientOrder $order, ClientOrderSubmission $submission, Collection $assignments, Collection $reviews, ?ClientInvoice $invoice): Collection
    {
        $timeline = collect();

        if ($submission->created_at) {
            $timeline->push([
                'label' => __('Manuscript submitted'),
                'description' => __('Manuscript received by the editorial office.'),
                'timestamp' => Carbon::parse($submission->created_at),
            ]);
        }

        if ($order->payment_status === PAYMENT_STATUS_PAID && $invoice) {
            $timeline->push([
                'label' => __('Payment confirmed'),
                'description' => __('Submission fee was paid successfully.'),
                'timestamp' => Carbon::parse($invoice->updated_at ?? $invoice->created_at),
            ]);
        }

        $firstAssignment = $assignments->sortBy('created_at')->first();
        if ($firstAssignment) {
            $timeline->push([
                'label' => __('Reviewers invited'),
                'description' => __('Review invitations were sent to experts.'),
                'timestamp' => Carbon::parse($firstAssignment->created_at),
            ]);
        }

        $firstCompletedReview = $reviews->filter(fn(Reviews $review) => $review->status === SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED)
            ->sortBy('submitted_at')
            ->first();
        if ($firstCompletedReview && ($firstCompletedReview->submitted_at || $firstCompletedReview->updated_at)) {
            $timeline->push([
                'label' => __('First review submitted'),
                'description' => __('A reviewer has provided feedback on your manuscript.'),
                'timestamp' => Carbon::parse($firstCompletedReview->submitted_at ?? $firstCompletedReview->updated_at),
            ]);
        }

        $timeline->push([
            'label' => __('Current status'),
            'description' => $this->formatSubmissionStatus($submission->approval_status ?? SUBMISSION_ORDER_STATUS_PENDING),
            'timestamp' => Carbon::now(),
            'is_current' => true,
        ]);

        return $timeline->sortBy('timestamp')->values();
    }

    protected function resolveExpectedDecisionDate(ClientOrderSubmission $submission, Collection $assignments): ?string
    {
        $dueDates = $assignments->pluck('due_at')->filter();

        if ($dueDates->isNotEmpty()) {
            return Carbon::parse($dueDates->min())->translatedFormat('F d, Y');
        }

        if ($submission->updated_at) {
            return Carbon::parse($submission->updated_at)->addWeeks(2)->translatedFormat('F d, Y');
        }

        return null;
    }

    protected function reviewChecklistLabels(): array
    {
        return [
            'title' => __('Title & Abstract'),
            'abstract' => __('Abstract Completeness'),
            'methods' => __('Methods & Design'),
            'ethics' => __('Ethics & Compliance'),
            'results' => __('Results & Analysis'),
            'discussion' => __('Discussion & Interpretation'),
            'references' => __('References'),
            'language' => __('Language & Style'),
            'figures' => __('Figures & Tables'),
        ];
    }

    protected function formatSubmissionStatus(string $status): string
    {
        $labels = [
            SUBMISSION_ORDER_STATUS_PENDING => __('Pending'),
            SUBMISSION_ORDER_STATUS_INCOMPLETE => __('Incomplete'),
            SUBMISSION_ORDER_STATUS_UNDER_PRIMARY_REVIEW => __('Under Primary Review'),
            SUBMISSION_ORDER_STATUS_INITIAL_ACCEPTED => __('Initial Accepted'),
            SUBMISSION_ORDER_STATUS_INITIAL_REJECTED => __('Initial Rejected'),
            SUBMISSION_ORDER_STATUS_PENDING_PAYMENT => __('Pending Payment'),
            SUBMISSION_ORDER_STATUS_PAYMENT_CONFIRMED => __('Payment Confirmed'),
            SUBMISSION_ORDER_STATUS_UNDER_PEER_REVIEW => __('Under Peer Review'),
            SUBMISSION_ORDER_STATUS_ACCEPTED_WITH_REVISIONS => __('Accepted with Revisions'),
            SUBMISSION_ORDER_STATUS_PEER_REJECTED => __('Peer Rejected'),
            SUBMISSION_ORDER_STATUS_ACCEPTED => __('Accepted'),
            SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION => __('Accepted for Publication'),
            SUBMISSION_ORDER_STATUS_PUBLISHED => __('Published'),
            SUBMISSION_ORDER_STATUS_ARCHIVED => __('Archived'),
            SUBMISSION_ORDER_STATUS_IN_FOLLOWUP => __('In Follow-up'),
        ];

        return $labels[$status] ?? Str::title(str_replace('_', ' ', $status));
    }

    protected function formatPaymentStatus(?string $status): string
    {
        $labels = [
            PAYMENT_STATUS_PENDING => __('Pending'),
            PAYMENT_STATUS_PAID => __('Paid'),
            PAYMENT_STATUS_PARTIAL => __('Partial'),
            PAYMENT_STATUS_CANCELLED => __('Cancelled'),
        ];

        return $labels[$status] ?? __('Pending');
    }

    // Task 7 & 8: Final Metadata Form
    public function finalMetadataForm(Request $request, $submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::with(['authors', 'journal', 'client_order.client'])
                ->where('id', $submissionId)
                ->firstOrFail();

            // Check if submission is accepted (in any accepted state) and metadata is pending
            $acceptedStatuses = [
                SUBMISSION_ORDER_STATUS_ACCEPTED,
                'proof_approved',
                'in_proofreading',
                SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
            ];

            if (!in_array($submission->approval_status, $acceptedStatuses)) {
                return redirect()->back()->with('error', __('This submission has not been accepted yet.'));
            }

            if ($submission->metadata_status !== 'pending_author') {
                return redirect()->back()->with('error', __('Final metadata form is not available at this time.'));
            }

            $data['pageTitle'] = __('Final Metadata Form');
            $data['submission'] = $submission;
            $data['activeOrder'] = 'active';

            return view('user.submission.final-metadata', $data);
        } catch (\Exception $e) {
            Log::error('Final metadata form error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred. Please try again.'));
        }
    }

    public function finalMetadataStore(Request $request)
    {
        try {
            DB::beginTransaction();

            $submissionId = decrypt($request->submission_id);
            $submission = ClientOrderSubmission::with('authors')->findOrFail($submissionId);

            // Validate submission status
            $acceptedStatuses = [
                SUBMISSION_ORDER_STATUS_ACCEPTED,
                'proof_approved',
                'in_proofreading',
                SUBMISSION_ORDER_STATUS_ACCEPTED_FOR_PUBLICATION
            ];

            if (
                !in_array($submission->approval_status, $acceptedStatuses) ||
                $submission->metadata_status !== 'pending_author'
            ) {
                return $this->error([], __('This form is not available at this time.'));
            }

            // Validate required fields
            $request->validate([
                'final_title' => 'required|string|max:500',
                'final_abstract' => 'required|string',
                'final_keywords' => 'required|string',
                'authors' => 'required|array|min:1',
                'authors.*.first_name' => 'required|string',
                'authors.*.last_name' => 'required|string',
                'authors.*.email' => 'required|email',
                'authors.*.affiliation' => 'required|string',
                'author_confirmed' => 'required|accepted',
            ]);

            // Create or update final metadata
            $finalMetadata = FinalMetadata::updateOrCreate(
                ['client_order_submission_id' => $submissionId],
                [
                    'final_title' => $request->final_title,
                    'short_title' => $request->short_title,
                    'final_abstract' => $request->final_abstract,
                    'final_keywords' => $request->final_keywords,
                    'funding_statement' => $request->funding_statement,
                    'conflict_statement' => $request->conflict_statement,
                    'acknowledgements' => $request->acknowledgements,
                    'notes_for_layout' => $request->notes_for_layout,
                    'author_confirmed' => true,
                ]
            );

            // Update author details if provided
            if ($request->has('authors')) {
                // Delete existing authors first
                AuthorDetails::where('client_order_submission_id', $submissionId)->delete();

                // Create new authors
                foreach ($request->authors as $index => $authorData) {
                    // Handle affiliation - convert to JSON if it's a string
                    $affiliation = $authorData['affiliation'] ?? '';
                    if (is_string($affiliation)) {
                        // If it's a string, convert to array format
                        $affiliation = [$affiliation];
                    }

                    AuthorDetails::create([
                        'client_order_submission_id' => $submissionId,
                        'first_name' => $authorData['first_name'],
                        'last_name' => $authorData['last_name'],
                        'email' => $authorData['email'],
                        'orcid' => $authorData['orcid'] ?? null,
                        'affiliation' => $affiliation,
                        'nationality' => $authorData['country'] ?? null,
                        'corresponding_author' => isset($authorData['corresponding_author']) ? (bool)$authorData['corresponding_author'] : false,
                    ]);
                }
            }

            // Update submission metadata status
            $submission->metadata_status = 'pending_editor_review';
            $submission->save();

            DB::commit();

            return $this->success([
                'redirect' => route('user.orders.dashboard', $submission->client_order_id)
            ], __('Final metadata submitted successfully. It will be reviewed by the editor.'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Final metadata store error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while saving. Please try again.'));
        }
    }

    public function downloadFinalAcceptanceCertificate(Request $request, $submission_id)
    {
        try {
            $submissionId = decrypt($submission_id);
            $submission = ClientOrderSubmission::where('id', $submissionId)
                ->where('client_order_id', function ($query) {
                    $query->select('order_id')
                        ->from('client_orders')
                        ->where('user_id', auth()->id())
                        ->limit(1);
                })
                ->firstOrFail();

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
                if (!file_exists($fullPath)) {
                    return redirect()->back()->with('error', __('Certificate file does not exist.'));
                }
                return response()->download($fullPath, $fileManager->original_name);
            } else {
                // For non-public drivers, stream the file
                if (!Storage::disk($storageDriver)->exists($filePath)) {
                    return redirect()->back()->with('error', __('Certificate file does not exist.'));
                }
                $fileContent = Storage::disk($storageDriver)->get($filePath);
                return response($fileContent, 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $fileManager->original_name . '"');
            }
        } catch (\Exception $e) {
            Log::error('Certificate download error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('An error occurred while downloading the certificate.'));
        }
    }
}
