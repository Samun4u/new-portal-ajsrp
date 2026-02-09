<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Services\FrontendService;
use App\Models\FrontendSection;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    protected $frontendService;

    public function __construct()
    {
        $this->frontendService = new FrontendService();
    }

    public function sectionSettingIndex(Request $request)
    {
        if ($request->ajax()) {
            return $this->frontendService->frontendSettingList();
        }

        $data['title'] = __('Frontend Section');
        $data['showFrontendSectionList'] = 'show active';
        $data['activeFrontendList'] = 'active';
        $data['subSectionSettingsActiveClass'] = 'active';
        return view('sadmin.setting.section-settings.index', $data);
    }

    public function frontendSectionInfo(Request $request)
    {
        $data['section'] = FrontendSection::findOrFail($request->id);
        return view('sadmin.setting.section-settings.edit-form', $data);
    }

    public function frontendSectionUpdate(Request $request)
    {
        return $this->frontendService->frontendSettingUpdate($request);
    }

    public function frontendSectionIndex()
    {
        $data['title'] = __('Frontend Setting');
        $data['showFrontendSectionList'] = 'show active';
        $data['activeFrontendList'] = 'active';
        $data['sectionSettingsActiveClass'] = 'active';
        return view('sadmin.setting.frontend-settings', $data);
    }
}
