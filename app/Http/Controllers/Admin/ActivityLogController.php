<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{

    public function index(Request $request)
    {
        $user_id= Auth::id();

        if($request->ajax()){
            if (!$user_id) {
                return redirect()->back()->with(['dismiss' => __('User Not found.')]);
            }
            $item = UserActivityLog::where(['user_id' => $user_id])->orderBy('id', 'DESC')->get();

            return datatables($item)
                ->addColumn('created_at', function ($item) {
                    return date('Y-m-d H:i:s', strtotime($item->created_at));
                })
                ->rawColumns(['created_at'])
                ->make(true);
        }
        $data['pageTitle'] = __('Activity Log');
        $data['activeClientActivityLog'] = 'active';
        $data['activeSetting'] = 'active';

        return view('admin.setting.activity-log.index', $data);
    }

}
