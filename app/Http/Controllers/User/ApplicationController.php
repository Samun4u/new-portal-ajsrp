<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreEditorialBoardApplicationRequest;
use App\Http\Requests\User\StoreReviewerApplicationRequest;
use App\Models\EditorialBoardApplication;
use App\Models\FileManager;
use App\Models\ReviewerApplication;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    use ResponseTrait;

    public function editorial_board_member(Request $request)
    {
        $data['pageTitleParent'] = __('Join');
        $data['pageTitle'] = __('Join Board Member');
        $data['activeEBMApplication'] = 'active';

        $countries = config('countries');
        $data['countries'] = $countries;
        
        return view('user.application.editorial-board-member.index', $data);
    }

    public function editorial_board_member_save(StoreEditorialBoardApplicationRequest $request)
    {
        try{

            DB::beginTransaction();
            $newFile = new FileManager();
            $cvUploadedFile = $newFile->upload('application', $request->cv);
            $cvFileId = $cvUploadedFile->id;

            $supportingDocUploadedFile = null;
            if ($request->hasFile('supporting_doc')) {
                $supportingDocUploadedFile = $newFile->upload('application', $request->supporting_doc);
                $supportingDocUrl = $supportingDocUploadedFile->id;
            }

            $photoUploadedFile = null;
            if ($request->hasFile('photo')) {
                $photoUploadedFile = $newFile->upload('application', $request->photo);
                $photoUrl = $photoUploadedFile->id;
            }

            // Prepare interests array
            $interests = $request->interests;
            if (in_array('Other', $interests) && $request->other_interest) {
                // Replace 'Other' with the specified interest
                $interests = array_diff($interests, ['Other']);
                $interests[] = $request->other_interest;
            }

            //Create the application
            $application = EditorialBoardApplication::create([
                'client_id' => auth()->user()->id,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country' => $request->country,
                'linkedin' => $request->linkedin,
                'degree' => $request->degree,
                'specialization' => $request->specialization,
                'title' => $request->title,
                'institution' => $request->institution,
                'experience' => $request->experience,
                'publications' => $request->publications,
                'supporting_doc_file_id' => $supportingDocUploadedFile ? $supportingDocUrl : null,
                'editorial_board_exp' => $request->editorial_board_exp == '1',
                'editorial_details' => $request->editorial_details,
                'peer_reviewer_exp' => $request->peer_reviewer_exp == '1',
                'reviewer_details' => $request->reviewer_details,
                'interests' => $interests,
                'other_interest' => $request->other_interest,
                'purpose' => $request->purpose,
                'cv_file_id' => $cvFileId,
                'photo_file_id' => $photoUploadedFile ? $photoUrl : null,
                'acknowledgment' => $request->has('acknowledgment')
            ]);

            DB::commit();

            
            //Send confirmation email
            newEBMSubmitEmailNotify($application->id);
            // newTicketNotify($application->id);


            return $this->success();


        }catch(Exception $e){
            $this->error([], $e->getMessage());
        }


    }

    public function become_a_reviewer(Request $request)
    {
        $data['pageTitleParent'] = __('Join');
        $data['pageTitle'] = __('Become a Reviewer');
        $data['activeReviewerApplication'] = 'active';

        $countries = config('countries');
        $data['countries'] = $countries;
        
        return view('user.application.become-a-reviewer.index', $data);
    }

    public function become_a_reviewer_save(StoreReviewerApplicationRequest $request)
    {
        // dd($request->all());

        // // Handle file uploads
        // $cvPath = $request->file('cv')->store('reviewer_applications/cvs', 'public');
        // $photoPath = null;
        
        // if ($request->hasFile('photo')) {
        //     $photoPath = $request->file('photo')->store('reviewer_applications/photos', 'public');
        // }

        // // Create application
        // ReviewerApplication::create([
        //     'full_name' => $request->full_name,
        //     'email' => $request->email,
        //     'institution' => $request->institution,
        //     'country' => $request->country,
        //     'orcid' => $request->orcid,
        //     'profile_links' => [
        //         'google_scholar' => $request->input('profile_links.google_scholar'),
        //         'linkedin' => $request->input('profile_links.linkedin'),
        //         'researchgate' => $request->input('profile_links.researchgate'),
        //         'website' => $request->input('profile_links.website'),
        //     ],
        //     'qualification' => $request->qualification,
        //     'field_of_study' => $request->field_of_study,
        //     'position' => $request->position,
        //     'experience_years' => $request->experience_years,
        //     'subject_areas' => $request->subject_areas,
        //     'keywords' => $request->keywords,
        //     'review_experience' => $request->review_experience,
        //     'cv_path' => $cvPath,
        //     'photo_path' => $photoPath,
        //     'agreement' => true,
        //     'consent_acknowledgment' => $request->acknowledgment === 'yes',
        // ]);

        // return response()->json(['success' => true]);
        try{

            DB::beginTransaction();
            $newFile = new FileManager();
            $cvUploadedFile = $newFile->upload('application', $request->cv);
            $cvFileId = $cvUploadedFile->id;

            $photoUploadedFile = null;
            if ($request->hasFile('photo')) {
                $photoUploadedFile = $newFile->upload('application', $request->photo);
                $photoUrl = $photoUploadedFile->id;
            }

            // Create application
            $application = ReviewerApplication::create([
                'client_id' => auth()->user()->id,
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
                'cv_path' => $cvFileId,
                'photo_path' => $photoUploadedFile ? $photoUrl : null,
                'agreement' => true,
                'consent_acknowledgment' => $request->acknowledgment === 'yes',
            ]);

            DB::commit();

            
            //Send confirmation email
            newReviewerApplicationSubmitEmailNotify($application->id);
            // newTicketNotify($application->id);


            return $this->success();

        }catch(Exception $e){
            $this->error([], $e->getMessage());
        }
    }
}
