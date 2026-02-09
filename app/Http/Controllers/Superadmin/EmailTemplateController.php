<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Services\SettingsService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    use ResponseTrait;
    public $settingsService;

    public function __construct()
    {
        $this->settingsService = new SettingsService();
    }

    public function emailTemplate(Request $request)
    {
        if(auth()->user()->role == USER_ROLE_SUPER_ADMIN){
          $data['title'] = __('Email Template');
        }else{
          $data['pageTitle'] = __('Email Template');
        }
        $data['showManageApplicationSetting'] = 'show';
        $data['activeEmailSetting'] = 'active';

        $data['emailTemplates'] = $this->settingsService->getEmailTemplate(auth()->user()->tenant_id);
        if (auth()->user()->role == USER_ROLE_SUPER_ADMIN) {
            return view('sadmin.setting.email_temp.email-temp', $data);
        } else {
            return view('admin.setting.email_temp.email-temp', $data);
        }
    }

    public function emailTemplateConfig(Request $request)
    {
        return $this->settingsService->emailTemplateConfig($request);
    }

    public function emailTemplateConfigUpdate(Request $request)
    {
        return $this->settingsService->emailTemplateConfigUpdate($request);
    }
}
