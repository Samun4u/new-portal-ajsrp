<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Models\Package;
use App\Models\SubscriptionOrder;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{

    use ResponseTrait;

    public $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request)
    {
        $data['title'] = __('Dashboard');
        $data['activeDashboard'] = 'active';
        $data['totalUser'] = User::where('role', USER_ROLE_ADMIN)->count();
        $data['totalClient'] = User::where('role', USER_ROLE_CLIENT)->count();
        $data['totalPackage'] = Package::count();
        $data['totalRevenue'] = SubscriptionOrder::where('payment_status', PAYMENT_STATUS_PAID)->sum('total');
        return view('sadmin.dashboard', $data);
    }

    public function userOverviewChartData(Request $request)
    {
        return $this->dashboardService->userOverviewChartData($request);
    }

    public function journalWorkflow()
    {
        // 1. Define the Workflow Structure
        // Strictly based on Journal_Workflow_Checklist.md
        $workflow = [
            [
                'id' => 's1',
                'name' => 'Submission & Registration',
                'tasks' => [
                    ['name' => 'Submit the manuscript', 'role' => 'Editorial Office', 'url' => route('admin.client-orders.list')],
                    ['name' => 'Upload all files', 'role' => 'Editorial Office'],
                    ['name' => 'Assign a reference ID', 'role' => 'Managing Editor']
                ]
            ],
            [
                'id' => 's2',
                'name' => 'Initial Check',
                'tasks' => [
                    ['name' => 'Check if the paper fits the journal', 'role' => 'Managing Editor'],
                    ['name' => 'Check for plagiarism/AI use', 'role' => 'Managing Editor'],
                    ['name' => 'Decide: reject now or move forward', 'role' => 'Editor-in-Chief / Managing Editor']
                ]
            ],
            [
                'id' => 's3',
                'name' => 'Peer Review',
                'tasks' => [
                    ['name' => 'Pick reviewers', 'role' => 'Managing Editor', 'url' => route('admin.reviewer.list')],
                    ['name' => 'Get review reports', 'role' => 'Managing Editor / Associate Editor'],
                    ['name' => 'Send feedback to authors', 'role' => 'Managing Editor'],
                    ['name' => 'Do re-review if needed', 'role' => 'Managing Editor / Associate Editor']
                ]
            ],
            [
                'id' => 's4',
                'name' => 'Thank Reviewers',
                'tasks' => [
                    ['name' => 'Send certificates to reviewers', 'role' => 'Editorial Office', 'url' => route('admin.certificate.reviewer.list')]
                ]
            ],
            [
                'id' => 's5',
                'name' => 'Final Acceptance',
                'tasks' => [
                    ['name' => 'Approve the final version', 'role' => 'Editor-in-Chief / Managing Editor'],
                    ['name' => 'Lock the content', 'role' => 'Managing Editor']
                ]
            ],
            [
                'id' => 's6',
                'name' => 'Proofreading & Metadata',
                'tasks' => [
                    ['name' => 'Fix language and formatting', 'role' => 'Language Editor'],
                    ['name' => 'Approve metadata', 'role' => 'Managing Editor']
                ]
            ],
            [
                'id' => 's7',
                'name' => 'Payment (APC)',
                'tasks' => [
                    ['name' => 'Send the invoice', 'role' => 'Managing Editor'],
                    ['name' => 'Record payment', 'role' => 'Finance Officer']
                ]
            ],
            [
                'id' => 's8',
                'name' => 'Acceptance Certificate',
                'tasks' => [
                    ['name' => 'Create the acceptance certificate', 'role' => 'Managing Editor']
                ]
            ],
            [
                'id' => 's9',
                'name' => 'Final Production',
                'tasks' => [
                    ['name' => 'Make final PDF/XML/HTML files', 'role' => 'Production/Technical Editor'],
                    ['name' => 'Assign to an issue', 'role' => 'Production/Technical Editor']
                ]
            ],
            [
                'id' => 's10',
                'name' => 'Publish Online',
                'tasks' => [
                    ['name' => 'Upload final files to system', 'role' => 'Technical Editor'],
                    ['name' => 'Submit metadata & DOI', 'role' => 'Technical Editor'],
                    ['name' => 'Final check before going live', 'role' => 'Managing Editor']
                ]
            ]
        ];

        // 2. Fetch Papers from DB
        // We use ClientOrderSubmission as the "Paper" entity
        $submissions = \App\Models\ClientOrderSubmission::with(['workflowHistories' => function($q) {
            $q->where('event_type', 'task_update')->orderBy('created_at', 'asc');
        }])->get();

        $papers = $submissions->map(function ($sub) {
            $statuses = [];
            foreach ($sub->workflowHistories as $history) {
                // Key by field slug (e.g. 'assign-reference-id')
                // Value is slugged status (e.g. 'completed')
                $statuses[$history->field] = Str::lower(Str::slug($history->to_value));
            }

            return [
                'id' => $sub->client_order_id, // Using Reference ID
                'submission_id' => $sub->id, // Primary Key for safe updates
                'title' => $sub->article_title,
                'statuses' => $statuses
            ];
        });

        return view('sadmin.journal.workflow', compact('workflow', 'papers'));
    }

    public function storePaper(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'id' => 'required|string|unique:client_order_submissions,client_order_id'
        ]);

        $paper = new \App\Models\ClientOrderSubmission();
        $paper->client_order_id = $request->id;
        $paper->article_title = $request->title;
        $paper->save();

        return response()->json([
            'success' => true,
            'message' => 'Paper added successfully',
            'submission_id' => $paper->id
        ]);
    }

    public function updateTaskStatus(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer',
            'task_name' => 'required|string',
            'status' => 'required|string',
            'stage_id' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            $paper = \App\Models\ClientOrderSubmission::findOrFail($request->submission_id);

            // Use updateOrCreate to prevent duplicates
            \App\Models\ClientOrderSubmissionWorkflowHistory::updateOrCreate(
                [
                    'client_order_submission_id' => $paper->id,
                    'event_type' => 'task_update',
                    'field' => Str::lower(Str::slug($request->task_name)), // Slug the key
                ],
                [
                    // User said "use lowercase and slug to save the values"
                    'to_value' => Str::lower(Str::slug($request->status)),
                    'actor_id' => auth()->id()
                ]
            );

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage());
        }
    }
}
