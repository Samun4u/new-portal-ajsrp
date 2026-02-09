<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ClientOrder;
use App\Models\ClientOrderSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = ClientOrder::where('user_id', $user->id)
            ->whereHas('submissions', function ($q) {
                $q->whereIn('approval_status', [
                    'accepted',
                    'in_proofreading',
                    'proof_approved',
                    'galley_in_progress',
                    'galley_ready',
                    'scheduled',
                    'published'
                ]);
            })
            ->with(['submissions.journal', 'submissions.issue', 'submissions.finalMetadata']);

        $data['pageTitle'] = __('Submission History');
        $data['submissions'] = $query->orderBy('created_at', 'desc')->paginate(20);
        $data['activeOrder'] = 'active';

        return view('user.submission-history.index', $data);
    }

    public function show($order_id)
    {
        $order = ClientOrder::where('order_id', $order_id)
            ->where('user_id', auth()->id())
            ->with(['submissions.journal', 'submissions.issue', 'submissions.finalMetadata', 'submissions.authors'])
            ->firstOrFail();

        $submission = $order->submissions->first();

        $data['pageTitle'] = __('Submission Details');
        $data['order'] = $order;
        $data['submission'] = $submission;
        $data['activeOrder'] = 'active';

        return view('user.submission-history.show', $data);
    }
}
