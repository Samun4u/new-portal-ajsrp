<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JournalCategoryRequest;
use App\Http\Requests\Admin\JournalRequest;
use App\Http\Requests\Admin\ServiceRequest;
use App\Http\Services\JournalManagerService;
use App\Models\FileManager;
use App\Models\Journal;
use App\Models\JournalSubject;
use App\Models\Service;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    use ResponseTrait;

    private $journalManagerService;

    public function __construct()
    {
        $this->journalManagerService = new JournalManagerService;
    }

    public function list(Request $request)
    {
        $data['pageTitle'] = 'Journal List';
        $data['breadcrumbArray'] = [];
        $data['activeJournal'] = 'active';
        $data['pageType'] = 0;
        if ($request->type != null) {
            $data['pageType'] = $request->type;
        }
        $data['journalList'] = $this->journalManagerService->list(20);
        return view('admin.journal.list', $data);
    }

    public function category_list(Request $request)
    {
        $data['pageTitle'] = 'Journal Category List';
        $data['breadcrumbArray'] = [];
        $data['activeJournalCategory'] = 'active';
        $data['pageType'] = 0;
        if ($request->type != null) {
            $data['pageType'] = $request->type;
        }
        $data['journalCategoryList'] = $this->journalManagerService->category_list(20);
        return view('admin.journal.category.list', $data);
    }

    public function addNew()
    {
        $data['pageTitleParent'] = __('Journal');
        $data['pageTitle'] = __('Add');
        $data['activeJournal'] = 'active';
        $data['journalCategoryList'] = JournalSubject::where(['status' => 'active'])->get();
        $data['serviceList'] = Service::where(['status' => ACTIVE])->get();
        return view('admin.journal.add-new', $data);
    }
    public function categoryAddNew()
    {
        $data['pageTitleParent'] = __('Journal');
        $data['pageTitle'] = __('Add Category');
        $data['activeJournalCategory'] = 'active';
        return view('admin.journal.category.add-new', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Journal');
        $data['pageTitle'] = __('Edit');
        $data['activeJournal'] = 'active';
        $data['journal'] = Journal::with('subjects')->where('id',decrypt($id))->first();
        $data['journalCategoryList'] = JournalSubject::where(['status' => 'active'])->get();
        $data['serviceList'] = Service::where(['status' => ACTIVE])->get();
        
        return view('admin.journal.edit', $data);
    }

    public function categoryEdit($id)
    {
        $data['pageTitleParent'] = __('Journal');
        $data['pageTitle'] = __('Edit Category');
        $data['activeJournalCategory'] = 'active';
        $data['journalCategory'] = JournalSubject::find(decrypt($id));
        return view('admin.journal.category.edit', $data);
    }

    public function details($id)
    {
        $data['pageTitleParent'] = __('Service');
        $data['pageTitle'] = __('Service Details');
        $data['activeService'] = 'active';
        $data['serviceDetails'] = Service::find(decrypt($id));
        return view('admin.service.details', $data);
    }


    public function store(JournalRequest $request)
    {
        return $this->journalManagerService->store($request);
    }

    public function categoryStore(JournalCategoryRequest $request)
    {
        return $this->journalManagerService->categoryStore($request);
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $serviceData = Service::where('id',decrypt($request->id))->first();
            $serviceData->delete();

            $file = FileManager::where('id', $serviceData->image)->first();
            if ($file) {
                $file->removeFile();
                $file->delete();
            }

            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }
    public function search(Request $request)
    {
        try {
            $data['journalList'] = Journal::where('title', 'LIKE', "%$request->keyword%")
                ->orderBy('id', 'DESC')
                ->get();
            $responseData = view('admin.journal.search-render', $data)->render();
            return $this->success($responseData, 'Data Found');
        } catch (\Exception $e) {
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function category_search(Request $request)
    {
        try {
            $data['journalCategoryList'] = JournalSubject::where(['status'=> 'active'])
                ->where('name', 'LIKE', "%$request->keyword%")
                ->orderBy('id', 'DESC')
                ->get();
            $responseData = view('admin.journal.category.search-render', $data)->render();
            return $this->success($responseData, 'Data Found');
        } catch (\Exception $e) {
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

}
