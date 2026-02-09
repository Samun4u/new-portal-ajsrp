<?php

namespace App\Providers;

use App\Models\Language;
use App\Models\Setting;
use App\Models\ClientOrderSubmission;
use App\Observers\ClientOrderSubmissionObserver;
use Illuminate\Database\Schema\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Socialite\OrcidProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Contracts\Factory;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);
        try {
            $connection = DB::connection()->getPdo();
            if ($connection) {
                $allOptions = [];
                $allOptions['settings'] = Setting::all()->pluck('option_value', 'option_key')->toArray();
                config($allOptions);

            }

            // // Get the session locale
            // $sessionLocale = Session::get('local');

            // // If session has a locale, check DB
            // if ($sessionLocale) {
            //     $language = Language::where('iso_code', $sessionLocale)->first();

            //     if ($language) {
            //         App::setLocale($language->iso_code);
            //     }
            // } else {
            //     // Fallback: use default
            //     App::setLocale(getDefaultLanguage());
            // }

            // $language = Language::where('iso_code', session()->get('local'))->first();

            // if ($language) {
                
            // }else {
            //     // 1. Detect language from browser (first 2 characters from Accept-Language header)
            //     $browserLang = substr(Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

            //     // 2. Get supported locales from database (iso_code column)
            //     $supportedLocales = Language::pluck('iso_code')->toArray();

            //     // 3. If the browser language exists in supported locales, return it
            //     if (in_array($browserLang, $supportedLocales)) {
            //         $ln = $browserLang;
            //         session(['local' => $ln]);
            //         App::setLocale(session()->get('local'));
            //     }else{
            //         // 4. If not, set to default language
            //         $language = Language::where('default', ACTIVE)->first();
            //         if ($language) {
            //             $ln = $language->iso_code;
            //             session(['local' => $ln]);
            //             App::setLocale(session()->get('local'));
            //         } else {
            //             // Fallback to English if no default language is set
            //             $ln = 'en';
            //             session(['local' => $ln]);
            //             App::setLocale(session()->get('local'));
            //         }
            //     }
            // }

            // if (!$language) {
            //     $language = Language::where('default', ACTIVE)->first();
            //     if ($language) {
            //         $ln = $language->iso_code;
            //         session(['local' => $ln]);
            //         App::setLocale(session()->get('local'));
            //     }else{
            //         $language = Language::where('iso_code', 'en')->first();
            //         if($language){
            //             $ln = $language->iso_code;
            //             session(['local' => $ln]);
            //             App::setLocale(session()->get('local'));
            //         }
            //     }
            // } else {
            //     $language = Language::where('default', ACTIVE)->first();
            //     if ($language) {
            //         $ln = $language->iso_code;
            //         session(['local' => $ln]);
            //         App::setLocale(session()->get('local'));
            //     }else{
            //         $language = Language::where('iso_code', 'en')->first();
            //         if($language){
            //             $ln = $language->iso_code;
            //             session(['local' => $ln]);
            //             App::setLocale(session()->get('local'));
            //         }
            //     }
            // }

            config(['app.defaultLanguage' => getDefaultLanguage()]);
            config(['app.currencySymbol' => getCurrencySymbol()]);
            config(['app.isoCode' => getIsoCode()]);
            config(['app.currencyPlacement' => getCurrencyPlacement()]);
            config(['app.debug' => getOption('app_debug', true)]);
            config(['app.timezone' => getOption('app_timezone','UTC')]);
            date_default_timezone_set( getOption('app_timezone','UTC'));

            config(['services.google.client_id' => getOption('google_client_id')]);
            config(['services.google.client_secret' => getOption('google_client_secret')]);
            config(['services.google.redirect' => url('auth/google/callback')]);

            config(['services.facebook.client_id' => getOption('facebook_client_id')]);
            config(['services.facebook.client_secret' => getOption('facebook_client_secret')]);
            config(['services.facebook.redirect' => url('auth/facebook/callback')]);
            if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1){
                config(['recaptchav3.sitekey' => getOption('google_recaptcha_site_key')]);
                config(['recaptchav3.secret' => getOption('google_recaptcha_secret_key')]);
            }

            View::share('totalMessage', 1);

            if (env('FORCE_SSL') == true) {
                URL::forceScheme('https');
            }


            //orcid registration
            $socialite = $this->app->make(Factory::class);
            $socialite->extend('orcid', function () use ($socialite) {
                $config = config('services.orcid');
                return $socialite->buildProvider(OrcidProvider::class, $config);
            });

            // Persist workflow history and auto-publish when publication_date is set
            ClientOrderSubmission::observe(ClientOrderSubmissionObserver::class);

        } catch (\Exception $e) {
            Log::info('Service Provider - ' . $e->getMessage());
        }
    }
}
