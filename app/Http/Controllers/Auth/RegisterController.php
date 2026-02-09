<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\FileManager;
use App\Models\Package;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            "email" => ['required', 'email', 'max:255', 'unique:users'],
            "name" => ['required', 'string', 'max:255'],
            "password" => ['required', 'string', 'min:6'],
        ];

        if (getOption('register_file_required', 0)) {
            $rules['file'] = ['bail', 'required', 'mimetypes:application/pdf'];
        }

        if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == STATUS_ACTIVE) {
            $rules['g-recaptcha-response'] = 'required|recaptchav3:register';
        }
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {

            DB::beginTransaction();

            $remember_token = Str::random(64);

            $usr = new User();
            $usr->name =  $data['name'];
            $usr->email =  $data['email'];
            $usr->password = Hash::make($data['password']);
            $usr->status = USER_STATUS_ACTIVE;
            $usr->role = USER_ROLE_CLIENT;
            $usr->email_verification_status = STATUS_ACTIVE;
            $usr->tenant_id = makeTenantId();
            $usr->remember_token = $remember_token;
            $usr->verify_token = str_replace('-', '', Str::uuid()->toString());

            //$usr->tenant_id = "zainiklab";

            $usr->save();
            
            DB::commit();
            
            //welcome mail sent 
            userWelcomeEmailNotify($usr);

            return $usr;
            
            /*DB::beginTransaction();
            $remember_token = Str::random(64);

            $google2fa = app('pragmarx.google2fa');

            $adminUser = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => USER_ROLE_CLIENT,
                'tenant_id' => makeTenantId(),
                'remember_token' => $remember_token,
                'status' => USER_STATUS_ACTIVE,
                'verify_token' => str_replace('-', '', Str::uuid()->toString()),
                'google2fa_secret' => $google2fa->generateSecretKey(),
            ]);

            // set admin gateway
            syncMissingGateway();

            // set admin email template
            setUserEmailTemplate($adminUser->tenant_id, $adminUser->id);

            // set default package
            $duration = (int) getOption('trail_duration', 1);
            $defaultPackage = Package::where(['is_trail' => ACTIVE])->first();
            if ($defaultPackage) {
                setUserPackage($adminUser->id, $defaultPackage, $duration);
            }

            $role = Role::where('name', 'Admin')->first();
            $role->syncPermissions(permissionArray());
            $adminUser->syncRoles($role->id);

            // notification call start
            $sAdminUser = User::where('tenant_id', null)->where('role', USER_ROLE_SUPER_ADMIN)->first();
            if ($sAdminUser) {
                setCommonNotification($sAdminUser->id, __('Have a new admin'), '', '');
            }
            DB::commit();

            return $adminUser;*/
        }catch (\Exception $e){
            DB::rollBack();
            throw ValidationException::withMessages([
                'body' => [SOMETHING_WENT_WRONG],
            ]);
        }

    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
        if (isAddonInstalled('ENCYSAAS') > 0) {
        } else {
            return redirect()->back()->with('error', 'Registration is not possible!');
        }
    }
}
