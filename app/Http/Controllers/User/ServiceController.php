<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\ServiceManagerService;
use App\Models\Service;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

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
        $data['pageTitle'] = __('Service');
        $data['activeService'] = 'active';
        $data['pageType'] = 0;
        if ($request->type != null) {
            $data['pageType'] = $request->type;
        }
        $data['serviceList'] = $this->serviceManagerService->clientListShow(10);

        return view('user.service.list', $data);
    }

    public function details($id)
    {
        $data['pageTitleParent'] = __('Service');
        $data['pageTitle'] = __('Service Details');
        $data['activeService'] = 'active';
        $data['serviceDetails'] = Service::find(decrypt($id));
        return view('user.service.details', $data);
    }

    public function search(Request $request)
    {
        try {
            $data['serviceList'] = Service::where(['tenant_id' => auth()->user()->tenant_id, 'status' => ACTIVE])
                ->where('service_name', 'LIKE', "%$request->keyword%")
                ->orderBy('id', 'DESC')
                ->get();
            $responseData = view('user.service.search-render', $data)->render();
            return $this->success($responseData, 'Data Found');
        } catch (\Exception $e) {
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }
}
