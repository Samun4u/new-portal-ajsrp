<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordChangeRequest;
use App\Http\Services\TeamMemberService;
use App\Http\Services\UserService;
use App\Models\FileManager;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Http\Requests\ProfileRequest;
use App\Models\ClientProfessionalDetails;
use App\Models\ClientPublicationDetails;
use App\Models\ClientResearchInformation;
use App\Models\EducationQualification;
use App\Models\UserDetails;
use BitPaySDK\Client;


class ProfileController extends Controller
{
    use ResponseTrait;

    public $userService, $teamMemberService;

    public function __construct()
    {
        $this->teamMemberService = new TeamMemberService;
        $this->userService = new UserService();
    }

    public function index()
    {
        $data['pageTitle'] = __('Profile');
        $data['activeSetting'] = 'active';
        $data['activeProfile'] = 'active';
        $data['user'] = $this->userService->userData();
        $data['userDetails'] = $this->userService->userBasicDetails($data['user']->id);
        $data['professionalDetails'] = $this->userService->professionalDetails($data['user']->id);
        $data['educationQualification'] = $this->userService->educationQualification($data['user']->id);
        $data['researchInformation'] = $this->userService->researchInformation($data['user']->id);
        $data['publicationDetails'] = $this->userService->publicationDetails($data['user']->id);
        $data['designations'] = $this->teamMemberService->getAllDesignation(auth()->id());
        return view('user.profile.index', $data);
    }

    public function password()
    {
        $data['pageTitleParent'] = __('Profile');
        $data['pageTitle'] = __('Change Password');
        $data['activeProfile'] = 'active';
        $data['activeSetting'] = 'active';
        return view('user.profile.password', $data);
    }

    public function update(Request $request)
    {
        try {
            $user = User::find(auth()->id());
            if(!$user){
                return $this->error([], __('User not found'));
            }
            if ($request->image) {
                $existFile = FileManager::where('id', $user->image)->first();
                if ($existFile) {
                    $existFile->removeFile();
                    $uploadedFile = $existFile->upload('User', $request->image, '', $existFile->id);
                    $user->image = $uploadedFile->id;
                } else {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('User', $request->image);
                    $user->image = $uploadedFile->id;
                }
            }
            
            //Client Basic details
            $user->name = $request->first_name .' '. $request->last_name;
            $user->mobile = $request->mobile;
            $user->date_of_birth = $request->date_of_birth;
            $user->whatsapp_number = $request->whatsapp_number;
            $user->save();
           
            $userDetails = UserDetails::where('user_id', $user->id)->first();
            if($userDetails){
                $userDetails->basic_first_name = $request->first_name;
                $userDetails->basic_middle_name = $request->middle_name;
                $userDetails->basic_last_name = $request->last_name;
                $userDetails->save();
            }else{
                UserDetails::create([
                    'user_id' => $user->id,
                    'basic_first_name' => $request->first_name,
                    'basic_middle_name' => $request->middle_name,
                    'basic_last_name' => $request->last_name
                ]);
            }
            

            //Client Professional details
            $clientProfessionalDetails = ClientProfessionalDetails::where('user_id', $user->id)->first();
            if ($clientProfessionalDetails) {
                $updateData = [
                    "title" => $request->title,
                    "title_spacify" => $request->title_other,
                    "highest_degree" => $request->degree,
                    "diploma_or_certifiction_spacify" => $request->degree_diploma,
                    "address" => $request->address,
                    "country" => $request->country,
                    "current_institution" => $request->institution,
                    "professional_bio" => $request->professionalBio,
                ];
                $clientProfessionalDetails->update($updateData);
            }else{
                ClientProfessionalDetails::create([
                    'user_id' => $user->id,
                    "title" => $request->title,
                    "title_spacify" => $request->title_other,
                    "highest_degree" => $request->degree,
                    "diploma_or_certifiction_spacify" => $request->degree_diploma,
                    "address" => $request->address,
                    "country" => $request->country,
                    "current_institution" => $request->institution,
                    "professional_bio" => $request->professionalBio,
                ]);
            }

            //Client research information
            $clientResearchInformation = ClientResearchInformation::where('user_id', $user->id)->first();
            if ($clientResearchInformation) {
                $updateData = [
                    "research_interest" => $request->researchInterests,
                    "orcid_id" => $request->orcidId,
                    "google_scholar_profile" => $request->scholarProfile, 
                ];    
                $clientResearchInformation->update($updateData);
            }else{
                ClientResearchInformation::create([
                    'user_id' => $user->id,
                    "research_interest" => $request->researchInterests,
                    "orcid_id" => $request->orcidId,
                    "google_scholar_profile" => $request->scholarProfile,     
                ]); 
            }

            // Education qualification
            $qualifications = $request->input('qualification');
            $qualificationFields = $request->input('qualification_field');

            $filteredQualifications = array_filter(
                $qualificationFields,
                function ($key) use ($qualifications) {
                    return in_array($key, $qualifications);
                },
                ARRAY_FILTER_USE_KEY
            );

            EducationQualification::where('user_id', $user->id)->delete();
            foreach($filteredQualifications as $key => $value){
                EducationQualification::create([
                    'user_id' => $user->id,
                    'qualification' => $key,
                    'field_in_study' => $value
                ]);
            }

            // Client Publication Details
            ClientPublicationDetails::where('user_id', $user->id)->delete();

            foreach ($request->publications as $publication) {
                ClientPublicationDetails::create([
                    'user_id' => $user->id,
                    'published_work' => $publication,
                ]);
            }
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function passwordUpdate(PasswordChangeRequest $request)
    {
        try {
            $user = User::find(auth()->id());
            if (Hash::check($request->current_password, $user->password) == false) {
                throw new Exception(__('Current Password Not Match'));
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function profileUpdate(ProfileRequest $request)
    {
        return $this->userService->profileUpdate($request);
    }

    public function addInstitution(Request $request)
    {

        return $this->userService->addInstitution($request);
    }

    public function changePasswordUpdate(Request $request)
    {
        return $this->userService->changePasswordUpdate($request);
    }

    public function security()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $google2fa = new Google2FA();
        $data['qr_code'] = $google2fa->getQRCodeInline(
            getOption('app_name'),
            $user->email,
            $user->google2fa_secret
        );
        return view('profile.security', $data);
    }

    public function smsSend(Request $request)
    {
        return $this->userService->smsSend($request);
    }
    public function smsReSend()
    {
        return $this->userService->smsReSend();
    }
    public function smsVerify(Request $request)
    {
        $request->validate([
            'opt-field.*' => 'required|numeric|',
        ]);
        return $this->userService->smsVerify($request);
    }
}
