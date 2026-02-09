<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Addon\Saas\FrontendController;
use App\Http\Requests\User\StoreReviewerApplicationRequest;
use App\Mail\ResearchSubmission;
use App\Models\Author;
use App\Models\FileManager;
use App\Models\Research;
use App\Models\ReviewerApplication;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class CommonController extends Controller
{
    public function index()
    {
        if (isAddonInstalled('ENCYSAAS') > 0) {
            $frontendController = new FrontendController;
            return $frontendController->index();
        }
        return redirect()->route('login');
    }

    public function authorsForm(Request $request)
    {
        $data['pageTitle'] = __('Authors Form');
        // Get client_order_id from query parameter if provided
        $data['client_order_id'] = $request->query('order_id', '');
        return view('frontend.authors_form.index', $data);
    }

    public function submit(Request $request)
    {
        // Validate the request
        $request->validate([
            'research_data' => 'required|json',
            'manuscript' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'client_order_id' => 'nullable|string',
        ]);

        // Parse the research data
        $researchData = json_decode($request->research_data, true);

        // dd($researchData);

        try {
            // Start database transaction
            DB::beginTransaction();

            // Get user_id if authenticated
            $userId = auth()->check() ? auth()->id() : null;

            // Get client_order_id from request
            $clientOrderId = $request->input('client_order_id', null);

            // Detect language from research data
            $language = !empty($researchData['research']['arabicTitle']) ? 'ar' : 'en';

            // Create research record
            $research = Research::create([
                'arabic_title' => $researchData['research']['arabicTitle'] ?? '',
                'english_title' => $researchData['research']['englishTitle'] ?? '',
                'field' => $researchData['research']['science'] ?? '',
                'other_field' => $researchData['research']['otherScience'] ?? null,
                'journal' => $researchData['research']['journal'] ?? '',
                'keywords' => $researchData['research']['keywords'] ?? null,
                'paper_id_ar' => $researchData['research']['paperIdAr'] ?? null,
                'paper_id_en' => $researchData['research']['paperIdEn'] ?? null,
                'thesis_answer' => $researchData['research']['thesisExtraction'] ?? null,
                'feedback' => $researchData['feedback'] ?? null,
                'client_order_id' => $clientOrderId,
                'user_id' => $userId,
                'language' => $language,
                'approval_status' => 'pending',
            ]);

            // Process file upload if exists
            if ($request->hasFile('manuscript')) {
                $filePath = $request->file('manuscript')->store('manuscripts');
                $research->update(['manuscript_path' => $filePath]);
                $researchData['manuscript_path'] = $filePath;
            }

            // Create author records
            foreach ($researchData['authors'] as $authorData) {

                // $affiliationAr = trim(($authorData['departmentAr'] ?? '') . ' - ' .
                //              ($authorData['facultyAr'] ?? '') . ' - ' .
                //              ($authorData['universityAr'] ?? '') . ' - ' .
                //              ($authorData['country'] ?? ''));

                $affiliationArParts = [
                    $authorData['departmentAr'] ?? '',
                    $authorData['facultyAr'] ?? '',
                    $authorData['universityAr'] ?? '',
                    $authorData['country'] ?? ''
                ];

                // Remove empty parts
                $affiliationArParts = array_filter($affiliationArParts);

                $affiliationAr = implode(' - ', $affiliationArParts);

                // $affiliationEn = trim(($authorData['departmentEn'] ?? '') . ' - ' .
                //              ($authorData['facultyEn'] ?? '') . ' - ' .
                //              ($authorData['universityEn'] ?? '') . ' - ' .
                //              ($authorData['country'] ?? ''));

                $affiliationEnParts = [
                    $authorData['departmentEn'] ?? '',
                    $authorData['facultyEn'] ?? '',
                    $authorData['universityEn'] ?? '',
                    $authorData['country'] ?? ''
                ];

                // Remove empty parts
                $affiliationEnParts = array_filter($affiliationEnParts);

                $affiliationEn = implode(' - ', $affiliationEnParts);

                Author::create([
                    'research_id' => $research->id,
                    'title_ar' => $authorData['titleAr'] ?? '',
                    'title_en' => $authorData['titleEn'] ?? '',
                    'title_value' => $authorData['titleValue'] ?? '',
                    'name_ar' => $authorData['nameAr'] ?? '',
                    'name_en' => $authorData['nameEn'] ?? '',
                    'email' => $authorData['email'] ?? '',
                    'phone' => $authorData['phone'] ?? '',
                    'degree_ar' => $authorData['degreeAr'] ?? '',
                    'degree_en' => $authorData['degreeEn'] ?? '',
                    'degree_value' => $authorData['degreeValue'] ?? '',
                    'affiliation_ar' => $affiliationAr,
                    'affiliation_en' => $affiliationEn,
                    'orcid' => $authorData['orcid'] ?? null,
                    'is_corresponding' => $authorData['corresponding'] ?? false,
                ]);
            }

            // Add submission timestamp to email data
            $researchData['submitted_at'] = now()->toDateTimeString();
            $researchData['research_id'] = $research->id;

            // Send email to admin using bilingual template
            Mail::to('submit@ajsrp.com')->send(new ResearchSubmission($researchData));

            // Send confirmation to corresponding author based on their language preference
            $correspondingAuthor = collect($researchData['authors'])->firstWhere('corresponding', true);
            if ($correspondingAuthor && isset($correspondingAuthor['email'])) {
                // Send bilingual confirmation email
                researchSubmissionConfirmationEmail($research->id, $correspondingAuthor['email'], $language);
            }

            // If there's a client_order_id, link the primary certificate to this research
            if ($clientOrderId) {
                $primaryCertificate = \App\Models\PrimaryCertificate::where('client_order_id', $clientOrderId)->first();
                if ($primaryCertificate) {
                    $primaryCertificate->research_id = $research->id;
                    $primaryCertificate->save();
                }
            }

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Research data submitted successfully',
                'research_id' => $research->id
            ]);

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            Log::error('Research submission error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your research. Please try again.'
            ], 500);
        }
    }

    public function thankYou()
    {
        return view('frontend.authors_form.thank-you');
    }

    // Add this method to your existing controller
    public function detectCountry(Request $request)
    {
        try {
            // Get client IP address
            $clientIP = $request->ip();

            Log::info('Client IP: ' . $clientIP);

            // If we're in development, use a test IP or service
            // if (app()->environment('local')) {
            //     // For development, return a default country
            //     return response()->json([
            //         'success' => true,
            //         'country_code' => 'PS', // Default to Saudi Arabia
            //         'country_name' => 'Palestine, State of',
            //         'country_name_ar' => 'فلسطين'
            //     ]);
            // }

            // Make request to ipapi.co from server side (no CORS issues)
            $response = Http::get("https://ipapi.co/{$clientIP}/json/");

            Log::info('API Response: ' . $response->body());

            if ($response->successful()) {
                $data = $response->json();
                Log::info('API Response Data: ' . json_encode($data));
                return response()->json([
                    'success' => true,
                    'country_code' => $data['country_code'] ?? 'PS',
                    'country_name' => $data['country_name'] ?? 'Palestine, State of',
                    'country_name_ar' => $this->getArabicCountryName($data['country_code'] ?? 'PS')
                ]);
            }

            // Fallback if API request fails
            return response()->json([
                'success' => true,
                'country_code' => 'PS',
                'country_name' => 'Palestine, State of',
                'country_name_ar' => 'فلسطين'
            ]);

        } catch (\Exception $e) {
            // Fallback in case of any errors
            return response()->json([
                'success' => true,
                'country_code' => 'PS',
                'country_name' => 'Palestine, State of',
                'country_name_ar' => 'فلسطين'
            ]);
        }
    }

    // Helper function to get Arabic country names
    private function getArabicCountryName($countryCode)
    {
        $countries = [
            "AF" => "أفغانستان",
            "AX" => "جزر أولاند",
            "AL" => "ألبانيا",
            "DZ" => "الجزائر",
            "AS" => "ساموا الأمريكية",
            "AD" => "أندورا",
            "AO" => "أنغولا",
            "AI" => "أنغويلا",
            "AQ" => "أنتاركتيكا",
            "AG" => "أنتيغوا وباربودا",
            "AR" => "الأرجنتين",
            "AM" => "أرمينيا",
            "AW" => "أروبا",
            "AU" => "أستراليا",
            "AT" => "النمسا",
            "AZ" => "أذربيجان",
            "BS" => "البهاما",
            "BH" => "البحرين",
            "BD" => "بنغلاديش",
            "BB" => "بربادوس",
            "BY" => "بيلاروس",
            "BE" => "بلجيكا",
            "BZ" => "بليز",
            "BJ" => "بنين",
            "BM" => "برمودa",
            "BT" => "بوتان",
            "BO" => "بوليفيا",
            "BQ" => "الجزر الكاريبية الهولندية",
            "BA" => "البوسنة والهرسك",
            "BW" => "بوتسوانا",
            "BV" => "جزيرة بوفيه",
            "BR" => "البرازيل",
            "IO" => "الإقليم البريطاني في المحيط الهندي",
            "BN" => "بروناي",
            "BG" => "بلغاريا",
            "BF" => "بوركينا فاسo",
            "BI" => "بوروندي",
            "CV" => "الرأس الأخضر",
            "KH" => "كمبوديا",
            "CM" => "الكاميرون",
            "CA" => "كندا",
            "KY" => "جزر كايمان",
            "CF" => "جمهورية أفريقيا الوسطى",
            "TD" => "تشاد",
            "CL" => "تشيلي",
            "CN" => "الصين",
            "CX" => "جزيرة الكريسماس",
            "CC" => "جزر كوكوس",
            "CO" => "كولومبيا",
            "KM" => "جزر القمر",
            "CG" => "الكونغo",
            "CD" => "جمهورية الكونغو الديمقراطية",
            "CK" => "جزر كوك",
            "CR" => "كوستاريكا",
            "CI" => "ساحل العاج",
            "HR" => "كرواتيا",
            "CU" => "كوبا",
            "CW" => "كوراساو",
            "CY" => "قبرص",
            "CZ" => "التشيك",
            "DK" => "الدنمارك",
            "DJ" => "جيبوتي",
            "DM" => "دومينيكا",
            "DO" => "جمهورية الدومينيكان",
            "EC" => "الإكوادور",
            "EG" => "مصر",
            "SV" => "السلفادور",
            "GQ" => "غينيا الاستوائية",
            "ER" => "إريتريا",
            "EE" => "إستونيا",
            "SZ" => "إسواتيني",
            "ET" => "إثيوبيا",
            "FK" => "جزر فوكلاند",
            "FO" => "جزر فارو",
            "FJ" => "فيجي",
            "FI" => "فنلندا",
            "FR" => "فرنسا",
            "GF" => "غويانا الفرنسية",
            "PF" => "بولينيزيا الفرنسية",
            "TF" => "الأقاليم الجنوبية الفرنسية",
            "GA" => "الغابون",
            "GM" => "غامبيا",
            "GE" => "جورجيا",
            "DE" => "ألمانيا",
            "GH" => "غانا",
            "GI" => "جبل طارق",
            "GR" => "اليونان",
            "GL" => "جرينلاند",
            "GD" => "غرينادا",
            "GP" => "غوادلوب",
            "GU" => "غوام",
            "GT" => "غواتيمالا",
            "GG" => "غيرنزي",
            "GN" => "غينيا",
            "GW" => "غينيا بيساو",
            "GY" => "غيانا",
            "HT" => "هايتي",
            "HM" => "جزيرة هيرد وجزر ماكدونالد",
            "VA" => "الفاتيكان",
            "HN" => "هندوراس",
            "HK" => "هونغ كونغ",
            "HU" => "هنغاريا",
            "IS" => "آيسلندا",
            "IN" => "الهند",
            "ID" => "إندونيسيا",
            "IR" => "إيران",
            "IQ" => "العراق",
            "IE" => "أيرلندا",
            "IM" => "جزيرة مان",
            "IL" => "إسرائيل",
            "IT" => "إيطاليا",
            "JM" => "جامايكا",
            "JP" => "اليابان",
            "JE" => "جيرسي",
            "JO" => "الأردن",
            "KZ" => "كازاخستان",
            "KE" => "كينيا",
            "KI" => "كيريباتي",
            "KP" => "كوريا الشمالية",
            "KR" => "كوريا الجنوبية",
            "KW" => "الكويت",
            "KG" => "قيرغيزستان",
            "LA" => "لاوس",
            "LV" => "لاتفيا",
            "LB" => "لبنان",
            "LS" => "ليسوتو",
            "LR" => "ليبيريا",
            "LY" => "ليبيا",
            "LI" => "ليختنشتاين",
            "LT" => "ليتوانيا",
            "LU" => "لوكسمبورغ",
            "MO" => "ماكاو",
            "MG" => "مدغشقر",
            "MW" => "مالاوي",
            "MY" => "ماليزيا",
            "MV" => "جزر المالديف",
            "ML" => "مالي",
            "MT" => "مالطا",
            "MH" => "جزر مارشال",
            "MQ" => "مارتينيك",
            "MR" => "موريتانيا",
            "MU" => "موريشيوس",
            "YT" => "مايوت",
            "MX" => "المكسيك",
            "FM" => "ميكرونيزيا",
            "MD" => "مولدوفا",
            "MC" => "موناكو",
            "MN" => "منغوليا",
            "ME" => "الجبل الأسود",
            "MS" => "مونتسرات",
            "MA" => "المغرب",
            "MZ" => "موزمبيق",
            "MM" => "ميانمار",
            "NA" => "ناميبيا",
            "NR" => "ناورو",
            "NP" => "نيبال",
            "NL" => "هولندا",
            "NC" => "كاليدونيا الجديدة",
            "NZ" => "نيوزيلندا",
            "NI" => "نيكاراغوا",
            "NE" => "النيجر",
            "NG" => "نيجيريا",
            "NU" => "نيوي",
            "NF" => "جزيرة نورفولك",
            "MK" => "مقدونيا الشمالية",
            "MP" => "جزر ماريانا الشمالية",
            "NO" => "النرويج",
            "OM" => "عمان",
            "PK" => "باكستان",
            "PW" => "بالاو",
            "PS" => "فلسطين",
            "PA" => "بنما",
            "PG" => "بابوا غينيا الجديدة",
            "PY" => "باراغواي",
            "PE" => "بيرو",
            "PH" => "الفلبين",
            "PN" => "جزر بيتكيرن",
            "PL" => "بولندا",
            "PT" => "البرتغال",
            "PR" => "بورتوريكو",
            "QA" => "قطر",
            "RE" => "ريونيون",
            "RO" => "رومانيا",
            "RU" => "روسيا",
            "RW" => "رواندا",
            "BL" => "سان بارتليمي",
            "SH" => "سانت هيلينا",
            "KN" => "سانت كيتس ونيفيس",
            "LC" => "سانت لوسيا",
            "MF" => "سانت مارتن",
            "PM" => "سان بيير وميكلون",
            "VC" => "سانت فنسنت والغرينادين",
            "WS" => "ساموا",
            "SM" => "سان مارينو",
            "ST" => "ساو تومي وبرينسيب",
            "SA" => "السعودية",
            "SN" => "السنغال",
            "RS" => "صربيا",
            "SC" => "سيشل",
            "SL" => "سيراليون",
            "SG" => "سنغافورة",
            "SX" => "سينت مارتن",
            "SK" => "سلوفاكيا",
            "SI" => "سلوفينيا",
            "SB" => "جزر سليمان",
            "SO" => "الصومال",
            "ZA" => "جنوب أفريقيا",
            "GS" => "جورجيا الجنوبية وجزر ساندويتش الجنوبية",
            "SS" => "جنوب السودان",
            "ES" => "إسبانيا",
            "LK" => "سريلانكا",
            "SD" => "السودان",
            "SR" => "سورينام",
            "SJ" => "سفالبارد ويان ماين",
            "SE" => "السويد",
            "CH" => "سويسرا",
            "SY" => "سوريا",
            "TW" => "تايوان",
            "TJ" => "طاجيكستان",
            "TZ" => "تنزانيا",
            "TH" => "تايلاند",
            "TL" => "تيمور الشرقية",
            "TG" => "توغو",
            "TK" => "توكيلau",
            "TO" => "تونغا",
            "TT" => "ترينيداد وتوباغو",
            "TN" => "تونس",
            "TR" => "تركيا",
            "TM" => "تركمانستان",
            "TC" => "جزر توركس وكايكوس",
            "TV" => "توفالو",
            "UG" => "أوغندا",
            "UA" => "أوكرانيا",
            "AE" => "الإمارات العربية المتحدة",
            "GB" => "المملكة المتحدة",
            "US" => "الولايات المتحدة",
            "UM" => "جزر الولايات المتحدة الصغيرة النائية",
            "UY" => "أوروغواي",
            "UZ" => "أوزبكستان",
            "VU" => "فانواتو",
            "VE" => "فنزويلا",
            "VN" => "فيتنام",
            "VG" => "جزر العذراء البريطانية",
            "VI" => "جزر العذراء الأمريكية",
            "WF" => "واليس وفوتونا",
            "EH" => "الصحراء الغربية",
            "YE" => "اليمن",
            "ZM" => "زامبيا",
            "ZW" => "زيمبابوي",
        ];

        return $countries[$countryCode] ?? 'فلسطين';
    }


    //become reviewer
    public function become_a_reviewer(){

        $data['pageTitle'] = __('Become a Reviewer');

        $countries = config('countries_data_ar_en');
        $data['countries'] = $countries;
        return view('frontend.application.become-a-reviewer.index', $data);
    }

    //become a reviewer save
    public function become_a_reviewer_save(StoreReviewerApplicationRequest $request){
        try{

            DB::beginTransaction();
            $newFile = new FileManager();

            // Validate file exists
            if (!$request->hasFile('cv')) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => __('CV file is required.')
                ], 422);
            }

            // Check file validity
            if (!$request->file('cv')->isValid()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => __('CV file is invalid or corrupted.')
                ], 422);
            }

            $cvUploadedFile = $newFile->upload('application', $request->cv);

            if (!$cvUploadedFile || !$cvUploadedFile->id) {
                DB::rollBack();
                Log::error('CV upload failed - FileManager returned null');
                Log::error('CV file details - name: ' . $request->file('cv')->getClientOriginalName() . ', size: ' . $request->file('cv')->getSize());
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to upload CV file. Please check file permissions and try again.')
                ], 422);
            }

            $cvFileId = $cvUploadedFile->id;

            $photoUploadedFile = null;
            $photoUrl = null;
            if ($request->hasFile('photo')) {
                $photoUploadedFile = $newFile->upload('application', $request->photo);
                if ($photoUploadedFile && $photoUploadedFile->id) {
                    $photoUrl = $photoUploadedFile->id;
                }
            }

            // Create application
            $application = ReviewerApplication::create([
                'client_id' => auth()->check() ? auth()->id() : null,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'institution' => $request->institution,
                'country' => $request->country,
                'orcid' => $request->orcid,
                'profile_links' => [
                    'google_scholar' => $request->input('profile_links.google_scholar'),
                    'linkedin' => $request->input('profile_links.linkedin'),
                    'researchgate' => $request->input('profile_links.researchgate'),
                    'website' => $request->input('profile_links.website'),
                ],
                'qualification' => $request->qualification,
                'field_of_study' => $request->field_of_study,
                'position' => $request->position,
                'experience_years' => $request->experience_years,
                'subject_areas' => $request->subject_areas,
                'keywords' => $request->keywords,
                'review_experience' => $request->review_experience,
                'cv_file_id' => $cvFileId,
                'photo_file_id' => $photoUploadedFile ? $photoUrl : null,
                'agreement' => true,
                'consent_acknowledgment' => $request->acknowledgment === 'yes',
                'status' => 'pending',
            ]);

            DB::commit();


            //Send confirmation email
            newReviewerApplicationSubmitEmailNotify($application->id);
            // newTicketNotify($application->id);


            // return $this->success();
             return response()->json([
                'success' => true,
                'message' => 'Your application has been submitted successfully!',
                'application_id' => $application->id
            ]);

        }catch(Exception $e){
            // $this->error([], $e->getMessage());
            Log::error('Reviewer application error: ' . $e->getMessage());
            Log::error('Reviewer application error trace: ' . $e->getTraceAsString());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while submitting your application. Please try again.') . ' ' . $e->getMessage()
            ], 500);
        }
    }
}
