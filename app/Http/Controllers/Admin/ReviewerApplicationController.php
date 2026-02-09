<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewerApplication;
use App\Models\User;
use App\Models\UserDetails;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ReviewerApplicationController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of reviewer applications.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getData($request);
        }

        $data['pageTitle'] = __('Reviewer Applications');
        $data['activeReviewerApplication'] = 'active';
        return view('admin.reviewer_application.list', $data);
    }

    /**
     * Get data for DataTable.
     */
    public function getData(Request $request)
    {
        $query = ReviewerApplication::with(['approvedUser', 'approver', 'cvFile', 'photoFile'])
            ->orderBy('created_at', 'desc')
            ->select('reviewer_applications.*');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->full_name;
            })
            ->addColumn('institution_country', function ($row) {
                return $row->institution . '<br><small>' . $row->country . '</small>';
            })
            ->addColumn('field', function ($row) {
                return $row->field_of_study;
            })
            ->addColumn('experience', function ($row) {
                return $row->experience_years . ' ' . __('years');
            })
            ->addColumn('status_badge', function ($row) {
                $badges = [
                    'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
                    'approved' => '<span class="badge bg-success">Approved</span>',
                    'rejected' => '<span class="badge bg-danger">Rejected</span>',
                ];
                return $badges[$row->status] ?? '<span class="badge bg-secondary">Unknown</span>';
            })
            ->addColumn('submitted_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i');
            })
            ->addColumn('action', function ($row) {
                $viewBtn = '<a href="' . route('admin.reviewer-application.show', $row->id) . '" class="btn btn-sm btn-info me-1" title="' . __('View') . '"><i class="fas fa-eye"></i></a>';

                $approveBtn = '';
                $rejectBtn = '';

                if ($row->status === 'pending') {
                    $approveBtn = '<button type="button" class="btn btn-sm btn-success me-1 approve-application-btn" data-id="' . $row->id . '" title="' . __('Approve & Create Reviewer') . '"><i class="fas fa-user-check"></i></button>';
                    $rejectBtn = '<button type="button" class="btn btn-sm btn-danger reject-application-btn" data-id="' . $row->id . '" title="' . __('Reject') . '"><i class="fas fa-times"></i></button>';
                }

                return $viewBtn . $approveBtn . $rejectBtn;
            })
            ->rawColumns(['institution_country', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Display the specified reviewer application.
     */
    public function show($id)
    {
        $data['pageTitle'] = __('Reviewer Application Details');
        $data['activeReviewerApplication'] = 'active';
        $data['application'] = ReviewerApplication::with(['approvedUser', 'approver', 'cvFile', 'photoFile'])
            ->findOrFail($id);

        return view('admin.reviewer_application.show', $data);
    }

    /**
     * Approve a reviewer application and create user account.
     */
    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $application = ReviewerApplication::findOrFail($id);

            // Check if already approved
            if ($application->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => __('This application has already been approved.')
                ], 400);
            }

            // Check if user with this email already exists
            $existingUser = User::where('email', $application->email)
                ->where('tenant_id', auth()->user()->tenant_id)
                ->first();

            if ($existingUser) {
                // If user exists, just update their role to include reviewer
                $user = $existingUser;
                if ($user->role != USER_ROLE_REVIEWER) {
                    $user->role = USER_ROLE_REVIEWER;
                    $user->save();
                }
            } else {
                // Create new user account for the reviewer
                $password = Str::random(12); // Generate random password

                $user = new User();
                $user->name = $application->full_name;
                $user->email = $application->email;
                $user->password = Hash::make($password);
                $user->role = USER_ROLE_REVIEWER;
                $user->status = STATUS_ACTIVE;
                $user->email_verified_at = now();
                $user->tenant_id = auth()->user()->tenant_id;
                $user->created_by = auth()->id();
                $user->save();

                // Create user details
                $userDetails = new UserDetails();
                $userDetails->user_id = $user->id;
                $userDetails->basic_company = $application->institution;
                $userDetails->billing_country = $application->country;
                $userDetails->save();

                // Copy expertise data from application to user for smart matching
                $user->field_of_study = $application->field_of_study;
                $user->subject_areas = $application->subject_areas;
                $user->expertise_keywords = $application->keywords;
                $user->experience_years = $application->experience_years;
                $user->save();

                // Send welcome email with credentials
                $this->sendReviewerWelcomeEmail($user, $password, $application);
            }

            // Update application status
            $application->status = 'approved';
            $application->approved_user_id = $user->id;
            $application->approved_at = now();
            $application->approved_by = auth()->id();
            $application->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('Application approved successfully. Reviewer account created.')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reviewer application approval error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('An error occurred while approving the application.')
            ], 500);
        }
    }

    /**
     * Reject a reviewer application.
     */
    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'required|string|min:10'
            ]);

            $application = ReviewerApplication::findOrFail($id);

            $application->status = 'rejected';
            $application->approved_at = now();
            $application->approved_by = auth()->id();
            $application->rejection_reason = $request->reason;
            $application->save();

            // Send rejection email
            $this->sendReviewerRejectionEmail($application);

            return response()->json([
                'success' => true,
                'message' => __('Application rejected successfully.')
            ]);

        } catch (\Exception $e) {
            Log::error('Reviewer application rejection error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('An error occurred while rejecting the application.')
            ], 500);
        }
    }

    /**
     * Send welcome email to newly approved reviewer.
     */
    private function sendReviewerWelcomeEmail($user, $password, $application)
    {
        try {
            if (getOption('app_mail_status')) {
                $loginUrl = route('login');

                $emailData = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $password,
                    'login_url' => $loginUrl,
                ];

                \Mail::to($user->email)->send(new \App\Mail\CustomEmailNotify('reviewer-approved', $emailData, 'reviewer-approved-welcome', $user, $loginUrl));
            }

            // Send in-app notification if they have client_id
            if ($application->client_id) {
                setCommonNotification(
                    $application->client_id,
                    __('Reviewer Application Approved'),
                    __('Congratulations! Your reviewer application has been approved. You can now log in to review papers.'),
                    route('login')
                );
            }
        } catch (\Exception $e) {
            Log::error('Reviewer welcome email error: ' . $e->getMessage());
        }
    }

    /**
     * Send rejection email to applicant.
     */
    private function sendReviewerRejectionEmail($application)
    {
        try {
            if (getOption('app_mail_status')) {
                \Mail::to($application->email)->send(new \App\Mail\CustomEmailNotify('reviewer-rejected', $application, 'reviewer-rejected-notify', null, ''));
            }

            // Send in-app notification if they have client_id
            if ($application->client_id) {
                setCommonNotification(
                    $application->client_id,
                    __('Reviewer Application Status'),
                    __('Thank you for your interest. After careful review, we are unable to approve your reviewer application at this time.'),
                    ''
                );
            }
        } catch (\Exception $e) {
            Log::error('Reviewer rejection email error: ' . $e->getMessage());
        }
    }
}

