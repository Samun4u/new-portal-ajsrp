<?php
namespace App\Http\Controllers\User;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use PharIo\Manifest\Exception;
use App\Http\Services\UserService;
use App\Mail\UserEmailVerification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    use ResponseTrait;
    public $userService ;
    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function emailVerified($token)
    {
        if (User::where('remember_token', $token)->count() > 0) {
            $user = User::where('remember_token', $token)->first();
            $user->status = USER_STATUS_ACTIVE;
            $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
            $user->email_verification_status = 1;
            $user->save();
            return redirect()->route('login')->with('success', __('Congratulations! Successfully verified your email.'));
        } else {
            return redirect(route('login'));
        }
    }

    public function emailVerify($token)
    {
        try {
            if(!request()->cookie('verify_email_send')){
                $user = User::where('verify_token', $token)->firstOrFail();
                Mail::to($user->email)->send(new UserEmailVerification($user));
                Cookie::queue('verify_email_send', true, 1);
                return redirect()->back()->with('success', __(SENT_SUCCESSFULLY));
            }
            else{
                return redirect()->back()->with('success',__('Already send an email. Please wait a minutes to try another'));
            }
        } catch (Exception $exception) {
            return redirect()->back()->with('error', __(SOMETHING_WENT_WRONG));
        }
    }

    public function emailVerifyResend($token)
    {
        try {
            if (getOption('email_verification_status', 0) == 1) {
                $user = User::where('remember_token', $token)->firstOrFail();
                Mail::to($user->email)->send(new UserEmailVerification($user));
                return redirect()->route('login')->with('success', __(SENT_SUCCESSFULLY));
            } else {
                return redirect()->route('login')->with('error', __(SOMETHING_WENT_WRONG));
            }
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', __(SOMETHING_WENT_WRONG));
        }
    }


    public function customerList(Request $request)
    {
        $data['pageTitle'] = __('Customers');
        $data['showCustomerList'] = 'show active';
        $data['activeCustomerList'] = 'active';

        if ($request->ajax()) {
            return $this->userService->customerListAll();
        }
        return view('user.customer.index', $data);
    }

    public function customerDelete($id)
    {
        return $this->userService->delete($id);
    }

    public function customerDetails($id)
    {
        $data['pageTitle'] = __('Customers');
        $data['showCustomerList'] = 'show active';
        $data['activeCustomerList'] = 'active';

        $data['customer_detail'] = $this->userService->details($id);
        return view('user.customer.customer_details', $data);
    }

}
