<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class LinkedInController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('linkedin-openid')
            // ->scopes(['r_liteprofile', 'r_emailaddress'])
            ->redirect();
    }

    public function callback()
    {
        try {

            $linkedinUser = Socialite::driver('linkedin-openid')->user();

            $findUser = User::where('linkedin_id', $linkedinUser->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect()->route('user.dashboard');
            }
            
            $data = new User();
            $data->linkedin_id = $linkedinUser->id;
            $data->name = $linkedinUser->name;
            $data->email = $linkedinUser->email;
            $data->linkedin_token = $linkedinUser->token;
            $data->linkedin_refresh_token = $linkedinUser->refreshToken;
            $data->password = bcrypt(Str::random(24)); // Dummy password
            $data->status = 1;
            $data->role = USER_ROLE_CLIENT;
            $data->tenant_id = "zainiklab";
            $data->save();

            //welcome mail sent
            userWelcomeEmailNotify($data);

            Auth::login($data);

            return redirect()->route('user.dashboard');
            

        } catch (\Exception $e) {
            return redirect('/login')->withErrors([
                'linkedin' => 'Failed to authenticate with LinkedIn'
            ]);
        }
    }
}
