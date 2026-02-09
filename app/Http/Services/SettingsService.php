<?php

namespace App\Http\Services;

use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    use ResponseTrait;


    public function cookieSettingUpdated($request)
    {

        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {


            $option = Setting::firstOrCreate(['option_key' => $key]);

            if ($request->hasFile('cookie_image') && $key == 'cookie_image') {
                $upload = settingImageStoreUpdate($value, $request->cookie_image);
                $option->option_value = $upload;
                $option->save();
            } else {
                $option->option_value = $value;
                $option->save();
            }
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function commonSettingUpdate($request)
    {
        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {


            $option = Setting::firstOrCreate(['option_key' => $key]);

            if ($request->hasFile('cookie_image') && $key == 'cookie_image') {
                $upload = settingImageStoreUpdate($value, $request->cookie_image);
                $option->option_value = $upload;
                $option->save();
            } else {
                $option->option_value = $value;
                $option->save();
            }
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }
    public function smsConfigurationStore($request)
    {
        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {

            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function getEmailTemplate($tenant_id = null)
    {
        return EmailTemplate::when(!isAddonInstalled('ENCYSAAS'),function($q) {
            $q->where('category', 'reset-password');
        })->orWhere('tenant_id', $tenant_id)->get();
    }

    public function emailTemplateConfig($request)
    {
        try {
            $data['template'] = EmailTemplate::find($request->id);
            $data['fields'] = customEmailTempFields($data['template']->category);
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function emailTemplateConfigUpdate($request)
    {
        DB::beginTransaction();
        try {
            $emailTemplate = EmailTemplate::findOrFail($request->id);
            $emailTemplate->title = $request->title;
            $emailTemplate->subject = $request->subject;
            $emailTemplate->body = $request->body;
            $emailTemplate->save();

            DB::commit();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}
