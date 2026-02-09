<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Http\Services\ServiceManagerService;
use App\Models\FileManager;
use App\Models\Service;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    use ResponseTrait;

    private $serviceManagerService;

    public function __construct()
    {
        $this->serviceManagerService = new ServiceManagerService;
    }

    public function list(Request $request)
    {
        $data['pageTitle'] = 'Service';
        $data['breadcrumbArray'] = [];
        $data['activeService'] = 'active';
        $data['pageType'] = 0;
        if ($request->type != null) {
            $data['pageType'] = $request->type;
        }
        $data['serviceList'] = $this->serviceManagerService->list(20);
        return view('admin.service.list', $data);
    }

    public function addNew()
    {
        $data['pageTitleParent'] = __('Service');
        $data['pageTitle'] = __('Add Service');
        $data['activeService'] = 'active';
        $data['teamMember'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();
        return view('admin.service.add-new', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Service');
        $data['pageTitle'] = __('Edit Service');
        $data['activeService'] = 'active';
        $data['serviceDetails'] = Service::find(decrypt($id));
        $data['teamMember'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();
        return view('admin.service.edit', $data);
    }

    public function details($id)
    {
        $data['pageTitleParent'] = __('Service');
        $data['pageTitle'] = __('Service Details');
        $data['activeService'] = 'active';
        $data['serviceDetails'] = Service::find(decrypt($id));
        return view('admin.service.details', $data);
    }


    public function store(ServiceRequest $request)
    {
        return $this->serviceManagerService->store($request);
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
            $data['serviceList'] = Service::where(['user_id'=> auth()->id(), 'status'=> ACTIVE])
                ->where('service_name', 'LIKE', "%$request->keyword%")
                ->orderBy('id', 'DESC')
                ->get();
            $responseData = view('admin.service.search-render', $data)->render();
            return $this->success($responseData, 'Data Found');
        } catch (\Exception $e) {
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

}
