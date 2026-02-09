<?php

namespace App\Http\Controllers\Superadmin;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\FeaturesSetting;
use App\Http\Controllers\Controller;
use App\Http\Services\ServiceSettingService;
use App\Http\Requests\ServiceSettingRequest;
use App\Models\ServiceSetting;

class ServiceSettingController extends Controller
{
    use ResponseTrait;
    protected $serviceSettingService;
    public function __construct()
    {
        $this->serviceSettingService = new ServiceSettingService();
    }

    public function index(Request $request)
    {
        $data['title'] = __('Service Setting Section');
        $data['activeFrontendList'] = 'active';
        $data['serviceActiveClass'] = 'active';
        if ($request->ajax()) {
            return $this->serviceSettingService->list();
        }
        return view('sadmin.setting.service.index', $data);
    }

    public function store(ServiceSettingRequest $request)
    {
        return $this->serviceSettingService->serviceSettingStore($request);
    }

    public function delete($id)
    {
        return $this->serviceSettingService->serviceDelete($id);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Service Section');

        try {
            $data['service'] = ServiceSetting::find($id);
            if (is_null($data['service'])) {
                return $this->error([], getMessage(SOMETHING_WENT_WRONG));
            }
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
        return view('sadmin.setting.service.edit-form', $data);;
    }
}
