<?php

use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\User\InvoiceController;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\VersionUpdateController;
use App\Http\Controllers\ReviewerInvitationController;
use App\Http\Controllers\User\GoogleAuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LinkedInController;
use App\Http\Controllers\Auth\OrcidController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/send', function () {
    return view('send');
})->name('front.send');

Route::post('/send', [App\Http\Controllers\User\OrderController::class, 'postsend'])->name('postsend');

Route::get('/local/{ln}', function ($ln) {
    $language = Language::where('iso_code', $ln)->first();
    if (!$language) {
        $language = Language::where('default', 1)->first();
        if ($language) {
            $ln = $language->iso_code;
        } else {
            // Fallback to Arabic if no default language found
            $ln = 'ar';
        }
    }
    session()->put('local', $ln);
    \Illuminate\Support\Facades\App::setLocale($ln);
    return redirect()->back();
})->name('local');



Auth::routes(['verify' => false]);

Route::group(['middleware' => ['isFrontend']], function () {
    Route::get('/', [CommonController::class, 'index'])->name('frontend');
});

Route::get('password/reset/verify/{token}/{email}', [ForgotPasswordController::class, 'forgetVerifyForm'])->name('password.reset.verify_form');
Route::get('password/reset/verify/{token}', [ForgotPasswordController::class, 'forgetVerify'])->name('password.reset.verify');
Route::post('password/reset/verify-resend/{token}', [ForgotPasswordController::class, 'forgetVerifyResend'])->name('password.reset.verify_resend');
Route::post('password/reset/update/{token}', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');

Route::group(['middleware' => ['auth']], function () {
    Route::get('logout', [LoginController::class, 'logout']);
    Route::get('google2fa/authenticate/verify', [GoogleAuthController::class, 'verifyView'])->name('google2fa.authenticate.verify');
    Route::post('google2fa/authenticate/verify/action', [GoogleAuthController::class, 'verify'])->name('google2fa.authenticate.verify.action');
    Route::post('google2fa/authenticate/enable', [GoogleAuthController::class, 'enable'])->name('google2fa.authenticate.enable');
    Route::post('google2fa/authenticate/disable', [GoogleAuthController::class, 'disable'])->name('google2fa.authenticate.disable');
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google-login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook-login');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

Route::get('/auth/linkedin', [LinkedInController::class, 'redirect'])->name('linkedin.login');
Route::get('/auth/linkedin/callback', [LinkedInController::class, 'callback']);


Route::get('/auth/orcid', [OrcidController::class, 'redirect'])->name('orcid.login');
Route::get('/auth/orcid/callback', [OrcidController::class, 'callback']);

Route::get('version-update', [VersionUpdateController::class, 'versionUpdate'])->name('version-update')->withoutMiddleware(['version.update']);
Route::post('process-update', [VersionUpdateController::class, 'processUpdate'])->name('process-update')->withoutMiddleware(['version.update']);

Route::get('invoice-maker', [InvoiceController::class, 'recurringInvoiceMaker'])->name('invoice-maker');

Route::get('checkout/{hash}', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('checkout/order', [CheckoutController::class, 'checkoutOrder'])->name('checkout.order');
Route::get('get-currency-by-gateway', [CheckoutController::class, 'getCurrencyByGateway'])->name('gateway.currency');
Route::get('get-coupon-info', [CheckoutController::class, 'getCouponInfo'])->name('get.coupon.info');

Route::match(array('GET', 'POST'), 'payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
Route::get('thankyou', [PaymentController::class, 'thankyou'])->name('thankyou');
Route::get('waiting', [PaymentController::class, 'waiting'])->name('waiting');
Route::get('failed', [PaymentController::class, 'failed'])->name('failed');

Route::get('invoice/{id}', [InvoiceController::class, 'invoiceDownload'])->name('invoice');

Route::get('quotation/preview/{id}/{view_status?}', [QuotationController::class, 'quotationPreview'])->name('quotation.preview');
Route::get('quotation/cancel/{id}/{view_status?}', [QuotationController::class, 'quotationCancel'])->name('quotation.cancel');
Route::get('quotation/print/{id}', [QuotationController::class, 'quotationPrint'])->name('quotation.print');

// Add this route
Route::get('/detect-country', [CommonController::class, 'detectCountry'])->name('detect.country');

Route::get('/authors-form', [CommonController::class, 'authorsForm'])->name('authors.form');
Route::post('/submit-research', [CommonController::class, 'submit'])->name('authors.form.submit');
Route::get('/thank-you', [CommonController::class, 'thankYou'])->name('authors.form.thankyou');
Route::get('/submit-feedback', [CommonController::class, 'submitFeedback'])->name('authors.form.feedback');

//Join as Editorial Board Member and reviewer
Route::group(['prefix' => 'join', 'as' => 'join.'], function () {
    Route::group(['prefix' => 'application', 'as' => 'application.'], function () {

        //Editorial Board Member
        // Route::group(['prefix' => 'editorial-board-member', 'as' => 'editorial-board-member.'], function () {
        //     Route::get('/', [ApplicationController::class, 'editorial_board_member'])->name('index');
        //     Route::post('/save', [ApplicationController::class, 'editorial_board_member_save'])->name('save');
        // });

        //Become a Reviewer
        Route::group(['prefix' => 'reviewer', 'as' => 'reviewer.'], function () {
            Route::get('/', [CommonController::class, 'become_a_reviewer'])->name('index');
            Route::post('/save', [CommonController::class, 'become_a_reviewer_save'])->name('save');
        });

        Route::group(['prefix' => 'reviewer/invitations', 'as' => 'reviewer.invitation.'], function () {
            Route::get('/{token}', [ReviewerInvitationController::class, 'show'])->name('show');
            Route::post('/{token}', [ReviewerInvitationController::class, 'respond'])->name('respond');
        });
    });
});

//=============================================
Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('optimize:clear');
    return "Config cache cleared!";
});


// Certificate System Routes
Route::group(['middleware' => ['auth']], function () {
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/create/{submission}', [App\Http\Controllers\CertificateController::class, 'create'])->name('create');
        Route::get('/view/{id}', [App\Http\Controllers\CertificateController::class, 'view'])->name('view');
        Route::get('/download/{id}', [App\Http\Controllers\CertificateController::class, 'download'])->name('download');
        Route::post('/download-image', [App\Http\Controllers\CertificateController::class, 'generatePdfFromImage'])->name('download.image');
    });

    // API routes for Certificate System (Web middleware for CSRF)
    Route::post('/api/certificates/generate', [App\Http\Controllers\CertificateController::class, 'generate']);
    Route::post('/api/certificates/upload-signature', [App\Http\Controllers\CertificateController::class, 'uploadSignature']);
    Route::get('/admin/submissions/journal/{journalId}/issues', [App\Http\Controllers\CertificateController::class, 'getIssues']);
});

Route::get('/verify-certificate/{certificate_number}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');

Route::get('get-certificate-template', function () {
    return view('tem.manuscript-certificate-english');
});
