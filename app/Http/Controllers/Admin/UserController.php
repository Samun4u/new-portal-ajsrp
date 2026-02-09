<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Services\UserService;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
     use ResponseTrait;

    private $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function index()
    {
        $data['title'] = 'All Users';
        $data['users'] = User::whereRole(1)->paginate(25);
        $data['navUserParentActiveClass'] = 'mm-active';
        $data['navUserParentShowClass'] = 'mm-show';
        $data['subNavUserActiveClass'] = 'mm-active';

        if(auth()->user()->role == USER_ROLE_SUPER_ADMIN){
            return view('sadmin.user.index', $data);
        }else{
            return view('admin.user.index', $data);
        }
    }

    public function userList(Request $request)
    {
        $data['pageTitle'] = 'All Users';

        if ($request->ajax()) {
            return $this->userService->customerListAll();
        }
        $data['activeUserList'] = 'active';

        if(auth()->user()->role == USER_ROLE_SUPER_ADMIN){
            return view('sadmin.user.index', $data);
        }else{
            return view('admin.user.index', $data);
        }
    }
    // userAdd

    public function userAdd()
    {
        $data['pageTitle'] = 'Add New User';
        $data['activeUserList'] = 'active';

        if(auth()->user()->role == USER_ROLE_SUPER_ADMIN){
            return view('sadmin.user.add-user', $data);
        }else{
            return view('admin.user.add-user', $data);
        }
    }

    public function store(UserRequest $request)
    {
        try {
            if(isAddonInstalled('ENCYSAAS')) {
                $userPackage = UserPackage::query()
                    ->where(['status' => ACTIVE, 'tenant_id' => Auth::user()->tenant_id])
                    ->whereDate('end_date', '>=', now()->toDateTimeString())
                    ->first();

                $totalClient = User::where(['role' => USER_ROLE_CLIENT, 'tenant_id' => auth()->user()->tenant_id])->count();

                if (is_null($userPackage) || ($userPackage->number_of_client <= $totalClient)) {
                    return $this->error([], __("Your client limit is over!"));
                }
            }
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->country = $request->country;
            $user->address = $request->address;
            $user->password = Hash::make($request->password);
            $user->role = auth()->user()->role == USER_ROLE_SUPER_ADMIN ? USER_ROLE_ADMIN : USER_ROLE_CLIENT ;
            $user->email_verification_status = $request->email_verification_status;
            $user->phone_verification_status = $request->phone_verification_status;
            if ($request->image) {
                $new_file = FileManager::where('id', $user->image)->first();
                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->upload('User', $request->image, '', $new_file->id);
                    $user->image = $upload->id;
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('User', $request->image);
                    $user->image = $upload->id;
                }
            }
            $user->save();
            DB::commit();
            return redirect()->route('admin.user.list')->with('success', CREATED_SUCCESSFULLY);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function userDetails($id)
    {
        $data['pageTitle'] = 'User Details';
        $data['activeUserList'] = 'active';
        $data['user'] = User::find($id);

        if(auth()->user()->role == USER_ROLE_SUPER_ADMIN){
            return view('sadmin.user.details-user', $data);
        }else{
            return view('admin.user.details-user', $data);
        }
    }

    public function edit($id)
    {
        $data['pageTitle'] = 'Edit User';
        $data['activeUserList'] = 'active';
        $data['user'] = User::find($id);

        if(auth()->user()->role == USER_ROLE_SUPER_ADMIN){
            return view('sadmin.user.edit-user', $data);
        }else{
            return view('admin.user.edit-user', $data);
        }
    }

    public function update(Request $request, $id)
    {
        if (User::whereEmail($request->email)->where('id', '!=', $id)->count() > 0)
        {
            $this->showToastrMessage('warning', 'Email already exist');
            return redirect()->back();
        }
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->country = $request->country;
        $user->address = $request->address;
        $user->role = auth()->user()->role == USER_ROLE_SUPER_ADMIN ? USER_ROLE_ADMIN : USER_ROLE_CLIENT ;
        $user->email_verification_status = $request->email_verification_status;
        $user->phone_verification_status = $request->phone_verification_status;
        if ($request->image) {
            $new_file = FileManager::where('id', $user->image)->first();
            if ($new_file) {
                $new_file->removeFile();
                $upload = $new_file->upload('User', $request->image, '', $new_file->id);
                $user->image = $upload->id;
            } else {
                $new_file = new FileManager();
                $upload = $new_file->upload('User', $request->image);
                $user->image = $upload->id;
            }
        }
        $user->save();
        return redirect()->back()->with('success', UPDATED_SUCCESSFULLY);
    }

    public function userSuspend($id)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            if ($user->status == STATUS_SUSPENDED) {
                $msg = __('Unsuspend Successfully');
            } else {
                $msg = __('Suspend Successfully');
            }
            $user->status = $user->status == STATUS_SUSPENDED ? STATUS_ACTIVE : STATUS_SUSPENDED;
            $user->save();
            DB::commit();
            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function userDelete($id)
    {
        try {
            DB::beginTransaction();
            $user = User::where('id', $id)->first();
            $user->delete();
            DB::commit();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }


}
