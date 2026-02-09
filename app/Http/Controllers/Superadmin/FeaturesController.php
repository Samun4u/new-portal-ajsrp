<?php

namespace App\Http\Controllers\Superadmin;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\FeaturesSetting;
use App\Http\Controllers\Controller;
use App\Http\Services\FeaturesSettingService;
use App\Http\Requests\FeaturesSettingRequest;

class FeaturesController extends Controller
{
    use ResponseTrait;
    protected $features;
    public function __construct()
    {
        $this->features = new FeaturesSettingService();
    }

    public function index(Request $request)
    {
        $data['title'] = __('Features Section');
        $data['activeFrontendList'] = 'active';
        $data['featuresActiveClass'] = 'active';
        if ($request->ajax()) {
            return $this->features->list();
        }
        return view('sadmin.setting.features.index', $data);
    }

    public function store(FeaturesSettingRequest $request)
    {
        return $this->features->featuresStore($request);
    }

    public function delete($id)
    {
        return $this->features->featuresDelete($id);
    }

    public function edit($id)
    {
        $data['title'] = __('Features Section');
        try {
            $data['features'] = FeaturesSetting::find($id);
            if (is_null($data['features'])) {
                return $this->error([], getMessage(SOMETHING_WENT_WRONG));
            }
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
        return view('sadmin.setting.features.edit-form', $data);;
    }
}
