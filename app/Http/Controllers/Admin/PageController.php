<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\PagesServices;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ResponseTrait;

class PageController extends Controller
{
    use ResponseTrait;
    private $pagesServices;
    
    public function __construct()
    {
        $this->pagesServices = new PagesServices();
    }


    public function list(Request $request){
        if ($request->ajax()) {
            return $this->pagesServices->getPageListData($request);
        } else {
            $data['pageTitle'] = __('Page list');
            $data['activePages'] = 'active';
            $data['pageList'] = Page::orderBy('id', 'desc')->get();
            return view('admin.pages.list', $data);
        }
        return view('admin.pages.list', $data);
    }

    public function add()
    {
        $data['pageTitle'] = __('Add Page');
        $data['activePages'] = 'active';
        return view('admin.pages.add', $data);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Edit Page');
        $data['activePages'] = 'active';
        $data['page'] = Page::find(decrypt($id));
        return view('admin.pages.edit', $data);
    }

    public function view($slug)
    {
        
        $data['pageTitle'] = __('View Page');
        $data['activePages'] = 'active';
        $data['page'] = Page::where('slug', $slug)->firstOrFail();
        return view('admin.pages.details', $data);
    }


    public function store(Request $request)
    {
        
        return $this->pagesServices->store($request);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $pageData = Page::where('id', decrypt($id))->first();
            $pageData->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }
}
