<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Services\DatabaseBackupSettingsService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
class DatabaseBackupSettingController extends Controller
{
    public $middleware = [];
    public $appSetting = null;
    public $reply = null;
    public $backupSetting = [];
    public $backups = [];

    public $databaseBackupSettingsService;

    public function __construct()
    {
        $this->databaseBackupSettingsService = new DatabaseBackupSettingsService();
    }

    public function index(Request $request)
    {
        $data['title'] = __('Backup');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeApplicationSetting'] = 'active';
        $data['subDatabaseBackupActiveClass'] = 'active-color-one';
        if ($request->ajax()) {
            return $this->databaseBackupSettingsService->index();
        }
        return view('admin.setting.database-backup-settings.index',$data);
    }

    public function createBackup($id)
    {
        try {
            if($id==1){
            Artisan::call('backup:run --only-db --disable-notifications');

            return redirect()->back()->with(['success'=>'Database Backed Up successfully']);
            }
            elseif($id==2){
                Artisan::call('backup:run --disable-notifications');
                return redirect()->back()->with(['success'=>'Source And Database Backed Up successfully']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['error'=>'Something Went Wrong!','backupResult'=>$e]);
        }

    }

    public function download($file_name)
    {
        try {
            $file =  "Laravel/".$file_name;
            if (Storage::disk('local')->exists($file)) {
                return Response::download(storage_path().'/'.'app/'.$file);
            }
            return redirect()->back()->with('error', 'Something Went Wrong!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something Went Wrong!');
        }
    }

    public function delete($file_name)
    {
        try {
            $file =  "Laravel/".$file_name;
            if (Storage::disk('local')->exists($file)) {
                Storage::disk('local')->delete($file);
                return redirect()->back()->with('success', 'File deleted successfully');
            }
            else{
                return redirect()->back()->with('success', 'File deleted successfully');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something Went Wrong!');
        }
    }

}
