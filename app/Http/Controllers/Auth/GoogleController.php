<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use App\Models\FileManager;

class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
    ->with(
        ['client_id' => config('services.sssgoogle.client_id')],
        ['client_secret' => config('services.sssgoogle.client_secret')],
        ['redirect_uri' => config('services.sssgoogle.redirect')]) // https://portal.ajsrp.com/auth/google/callback
    ->redirect();
        //return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->with([
                        'client_id' => config('services.sssgoogle.client_id'),
                        'client_secret' => config('services.sssgoogle.client_secret')])
                    ->user();
            
            $findUser = User::where('google_id', $user->id)->first();
            //dd($findUser);
            
            if ($findUser) {
                
                Auth::login($findUser);
                
                return redirect()->route('user.dashboard');
                
            } else {
                
                DB::beginTransaction();
    
                $data = new User();
                $data->google_id = $user->id;
                //$data->google_auth_status = 1;
                $data->name = $user->name;
                $data->email = $user->email;
                $data->status = 1;
                $data->role = USER_ROLE_CLIENT;
                $data->email_verification_status = STATUS_ACTIVE;
                //$data->created_by = auth()->id();
                /*if ($request->client_password) {
                    $data->password = Hash::make($request->client_password);
                }*/
                //$data->mobile = $request->client_phone_number;
                //$data->company_name = $request->client_company_name;
                
                $data->tenant_id = "zainiklab";
    
                $data->save();
                
                if ($user->avatar) {
                    
                    $extension = "png";
                    //$size = $file->getSize();
                    
                    $file_name = $user->id.'.png';
                    $originalName = $user->id;
        
                    \Storage::disk(config('app.STORAGE_DRIVER'))
                        ->put('uploads/Service/' . $file_name, file_get_contents($user->avatar));

                    $fileManager = new FileManager();
                    $fileManager->file_type = "image/png";
                    $fileManager->storage_type = config('filesystems.default');
                    $fileManager->original_name = $originalName;
                    $fileManager->file_name = $file_name;
                    $fileManager->user_id = $data->id;
                    $fileManager->path = 'uploads/Service/'.$file_name;
                    $fileManager->extension = $extension;
                    //$fileManager->size = $size;
                    $fileManager->save();
                    
                    $data->image = $fileManager->id;
                    $data->save();
                }
                DB::commit();
                
                //welcome mail sent
                userWelcomeEmailNotify($data);
            
                Auth::login($data);
                return redirect()->route('user.dashboard');
                
                
            }
            
            return redirect(route('login'))->with('error', __("You have to registered first to login with google"));

        } catch (Exception $e) {
            dd($e->getMessage());
           //return redirect(route('login'))->with('error', $e->getMessage());
        }
    }
}
