<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Services\UserService;
use App\Models\User;
use App\Models\UserPackage;
use App\Traits\General;
use App\Models\FileManager;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\UserActivityLog;
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

        return view('sadmin.user.index', $data);
    }

    public function userList(Request $request)
    {
        $data['title'] = 'All Users';

        if ($request->ajax()) {
            return $this->userService->customerListAll();
        }
        $data['activeUserList'] = 'active';

        return view('sadmin.user.index', $data);
    }

    public function userAdd()
    {
        $data['title'] = 'Add New User';
        $data['activeUserList'] = 'active';
        return view('sadmin.user.add-user', $data);
    }

    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->country = $request->country;
            $user->address = $request->address;
            $user->password = Hash::make($request->password);
            $user->role =  USER_ROLE_ADMIN;
            $user->tenant_id = makeTenantId();
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

            // set admin gateway
            syncMissingGateway();

            // set admin email template
            setUserEmailTemplate($user->tenant_id, $user->id);

            // set default package
            $duration = (int) getOption('trail_duration', 1);
            $defaultPackage = Package::where(['is_trail' => ACTIVE])->first();
            if ($defaultPackage) {
                setUserPackage($user->id, $defaultPackage, $duration);
            }

            $role = Role::where('name', 'Admin')->first();
            $role->syncPermissions(permissionArray());
            $user->syncRoles($role->id);

            DB::commit();

            return redirect()->route('super-admin.user.list')->with('success', __(CREATED_SUCCESSFULLY));
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

        return view('sadmin.user.details-user', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Edit User';
        $data['activeUserList'] = 'active';
        $data['user'] = User::find($id);

        return view('sadmin.user.edit-user', $data);
    }

    public function update(Request $request, $id)
    {
        if (User::whereEmail($request->email)->where('id', '!=', $id)->count() > 0) {
            $this->showToastrMessage('warning', 'Email already exist');
            return redirect()->back();
        }
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->country = $request->country;
        $user->address = $request->address;
        $user->role =  USER_ROLE_ADMIN;
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

        return redirect()->back()->with('success', __(UPDATED_SUCCESSFULLY));
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

    public function userActivity(Request $request, $user_id)
    {
        if ($request->ajax()) {

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
    }

    public function packagesHistory(Request $request, $user_id)
    {
        if ($request->ajax()) {

            if (!$user_id) {
                return redirect()->back()->with(['dismiss' => __('User Not found.')]);
            }
            $item = UserPackage::join('packages', 'user_packages.package_id', '=', 'packages.id')
                ->join('subscription_orders', 'user_packages.order_id', '=', 'subscription_orders.id')
                ->select('subscription_orders.payment_status', 'user_packages.*')
                ->where(['user_packages.user_id' => $user_id])
                ->orderBy('user_packages.id', 'DESC')
                ->get();

            return datatables($item)
                ->addColumn('payment_status', function ($userPackage) {
                    if ($userPackage->payment_status == ORDER_PAYMENT_STATUS_PAID) {
                        return '<div class="zBadge zBadge-paid">' . __('Paid') . '</div>';
                    } elseif ($userPackage->payment_status == ORDER_PAYMENT_STATUS_PENDING) {
                        return '<div class="zBadge zBadge-pending">' . __('Pending') . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-cancel>' . __('Cancelled') . '</div>';
                    }
                })
                ->addColumn('start_date', function ($userPackage) {
                    return date('Y-m-d', strtotime($userPackage->start_date));
                })
                ->addColumn('end_date', function ($userPackage) {
                    return date('Y-m-d', strtotime($userPackage->end_date));
                })
                ->addColumn('status', function ($userPackage) {
                    if ($userPackage->status == ACTIVE) {
                        return '<div class="zBadge zBadge-active">Active</div>';
                    } else {
                        return '<div class="zBadge zBadge-inactive">Deactivate</div>';
                    }
                })
                ->rawColumns(['payment_status', 'status', 'start_date', 'end_date'])
                ->make(true);
        }
    }

    public function userDelete($id)
    {
        try {
            DB::beginTransaction();
            $user = User::where('id', $id)->first();
            $user->delete();
            DB::commit();
            $message = getMessage(__(DELETED_SUCCESSFULLY));
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}
