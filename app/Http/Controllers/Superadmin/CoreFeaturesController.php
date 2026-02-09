<?php

namespace App\Http\Controllers\Superadmin;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoreFeaturesSettingRequest;
use App\Http\Services\CoreFeaturesSettingService;
use App\Models\CoreFeaturesSetting;

class CoreFeaturesController extends Controller
{
    use ResponseTrait;
    protected $coreFeatures;
    public function __construct()
    {
        $this->coreFeatures = new CoreFeaturesSettingService();
    }

    public function index(Request $request){
        $data['title'] = __('Core Features Section');
        $data['activeFrontendList'] = 'active';
        $data['coreFeaturesActiveClass'] = 'active';
        if($request->ajax()){
            return $this->coreFeatures->list();
        }
        return view('sadmin.setting.core_features.index', $data);
    }

    public function store(CoreFeaturesSettingRequest $request){
        return $this->coreFeatures->bestFeaturesStore($request);
    }

    public function delete($id){
        return $this->coreFeatures->featuresDelete($id);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Core Features Section');
        try {
            $data['features'] = CoreFeaturesSetting::find($id);
            if (is_null($data['features'])) {
                return $this->error([], getMessage(SOMETHING_WENT_WRONG));
            }
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
        return view('sadmin.setting.core_features.edit-form', $data);;
    }


}
