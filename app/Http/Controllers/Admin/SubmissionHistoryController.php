<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientOrder;
use App\Models\ClientOrderSubmission;
use App\Models\Journal;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientOrderSubmission::with(['journal', 'issue', 'client_order.client', 'authors']);

        // Filters
        if ($request->status) {
            $query->where('approval_status', $request->status);
        }
        if ($request->journal_id) {
            $query->where('journal_id', $request->journal_id);
        }
        if ($request->issue_id) {
            $query->where('issue_id', $request->issue_id);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->author_search) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->author_search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->author_search . '%')
                    ->orWhere('email', 'like', '%' . $request->author_search . '%');
            });
        }

        $data['pageTitle'] = __('Submission History');
        $data['submissions'] = $query->orderBy('created_at', 'desc')->paginate(20);
        $data['journals'] = Journal::where('status', 'active')->get();
        $data['issues'] = Issue::all();
        $data['filters'] = $request->only(['status', 'journal_id', 'issue_id', 'date_from', 'date_to', 'author_search']);
        $data['activeOrder'] = 'active';

        return view('admin.submission-history.index', $data);
    }
}
