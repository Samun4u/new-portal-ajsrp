<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Journal;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IssueController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $query = Issue::with('journal');

        // Filters
        if ($request->journal_id) {
            $query->where('journal_id', $request->journal_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->year) {
            $query->whereYear('year', $request->year);
        }

        $data['pageTitle'] = __('Issues Management');
        $data['issues'] = $query->orderBy('year', 'desc')->orderBy('volume', 'desc')->orderBy('number', 'desc')->paginate(20);
        $data['journals'] = Journal::where('status', 'active')->get();
        $data['activeOrder'] = 'active';
        $data['filters'] = $request->only(['journal_id', 'status', 'year']);

        return view('admin.issues.list', $data);
    }

    public function create()
    {
        $data['pageTitle'] = __('Create Issue');
        $data['journals'] = Journal::where('status', 'active')->get();
        $data['activeOrder'] = 'active';
        return view('admin.issues.create', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'journal_id' => 'required|exists:journals,id',
                'volume' => 'nullable|integer',
                'number' => 'nullable|integer',
                'year' => 'nullable|integer|min:2000|max:2100',
                'title' => 'nullable|string|max:255',
                'status' => 'required|in:planned,scheduled,published',
                'planned_publication_date' => 'nullable|date',
            ]);

            $issue = Issue::create($request->only([
                'journal_id',
                'volume',
                'number',
                'year',
                'title',
                'status',
                'planned_publication_date'
            ]));

            DB::commit();
            return $this->success(['redirect' => route('admin.issues.show', $issue->id)], __('Issue created successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Issue store error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while creating issue.'));
        }
    }

    public function show($id)
    {
        $issue = Issue::with([
            'journal',
            'submissions' => function ($q) {
                $q->with(['authors', 'journal'])->orderBy('article_title');
            }
        ])->findOrFail($id);

        $data['pageTitle'] = __('Issue Details');
        $data['issue'] = $issue;
        $data['submissions'] = $issue->submissions;
        $data['activeOrder'] = 'active';

        return view('admin.issues.show', $data);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Edit Issue');
        $data['issue'] = Issue::findOrFail($id);
        $data['journals'] = Journal::where('status', 'active')->get();
        $data['activeOrder'] = 'active';
        return view('admin.issues.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $issue = Issue::findOrFail($id);

            $request->validate([
                'journal_id' => 'required|exists:journals,id',
                'volume' => 'nullable|integer',
                'number' => 'nullable|integer',
                'year' => 'nullable|integer|min:2000|max:2100',
                'title' => 'nullable|string|max:255',
                'status' => 'required|in:planned,scheduled,published',
                'planned_publication_date' => 'nullable|date',
                'publication_date' => 'nullable|date',
            ]);

            $issue->update($request->only([
                'journal_id',
                'volume',
                'number',
                'year',
                'title',
                'status',
                'planned_publication_date',
                'publication_date'
            ]));

            DB::commit();
            return $this->success(['redirect' => route('admin.issues.show', $issue->id)], __('Issue updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Issue update error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while updating issue.'));
        }
    }

    public function destroy($id)
    {
        try {
            $issue = Issue::findOrFail($id);

            if ($issue->submissions()->count() > 0) {
                return $this->error([], __('Cannot delete issue with assigned submissions.'));
            }

            $issue->delete();
            return $this->success([], __('Issue deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Issue delete error: ' . $e->getMessage());
            return $this->error([], __('An error occurred while deleting issue.'));
        }
    }

    public function getByJournal(Request $request)
    {
        try {
            $journalId = $request->journal_id;
            $issues = Issue::where('journal_id', $journalId)
                ->orderBy('year', 'desc')
                ->orderBy('volume', 'desc')
                ->orderBy('number', 'desc')
                ->get(['id', 'volume', 'number', 'year', 'title', 'status']);

            return response()->json([
                'success' => true,
                'issues' => $issues
            ]);
        } catch (\Exception $e) {
            Log::error('Get issues by journal error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'issues' => []
            ]);
        }
    }

    public function journalIssues(Request $request)
    {
        $query = Journal::where('status', 'active')
            ->withCount(['issues' => function ($q) {
                $q->whereIn('status', ['planned', 'scheduled', 'published']);
            }])
            ->with(['issues' => function ($q) {
                $q->withCount('submissions')
                    ->orderBy('year', 'desc')
                    ->orderBy('volume', 'desc')
                    ->orderBy('number', 'desc');
            }]);

        if ($request->journal_id) {
            $query->where('id', $request->journal_id);
        }

        $journals = $query->get()->filter(function ($journal) {
            return $journal->issues_count > 0 || !request('journal_id');
        });

        $data['pageTitle'] = __('Journal Issues Overview');
        $data['journals'] = $journals;
        $data['allJournals'] = Journal::where('status', 'active')->get();
        $data['activeJournalIssues'] = 'active';
        $data['filters'] = $request->only(['journal_id']);

        return view('admin.issues.journal-issues-overview', $data);
    }
}
