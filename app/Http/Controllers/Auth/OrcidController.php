<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class OrcidController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('orcid')->redirect();
    }

    public function callback()
    {
        try {
            $orcidUser = Socialite::driver('orcid')->user();

            // Check if the user already exists
            $existingUser = User::where('orcid_id', $orcidUser->id)->first();

            if ($existingUser) {
                Auth::login($existingUser);
                return redirect()->route('user.dashboard');
            }
            // If the user does not exist, create a new user
            $name = $orcidUser->user['given_name'];

            $user = new User();
            $user->orcid_id = $orcidUser->id;
            $user->name = $name ?? "ORCID User " . $orcidUser->id;
            $user->email = $orcidUser->email ?? $this->generateOrcidEmail($orcidUser->id);
            $user->orcid_token = $orcidUser->token;
            $user->orcid_refresh_token = $orcidUser->refreshToken;
            $user->password = bcrypt(Str::random(24)); // Dummy password
            $user->status = 1;
            $user->role = USER_ROLE_CLIENT;
            $user->tenant_id = "zainiklab"; // Set tenant_id if needed
            $user->save();
            
            //welcome mail sent
            userWelcomeEmailNotify($user);

            Auth::login($user);

            return redirect()->route('user.dashboard');

        } catch (\Exception $e) {
            Log::error('ORCID Auth Error: ' . $e->getMessage());
            return redirect('/login')->withErrors([
                'orcid' => 'Failed to authenticate with ORCID'
            ]);
        }
    }

    protected function generateOrcidEmail($orcidId)
    {
        return str_replace('-', '', $orcidId) . '@orcid.invalid';
    }
}