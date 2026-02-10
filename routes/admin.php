<?php

use App\Http\Controllers\AddonUpdateController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BookSubmissionController;
use App\Http\Controllers\Admin\ResearchSubmissionController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientInvoiceController;
use App\Http\Controllers\Admin\ClientOrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\EditorDashboardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Superadmin\DashboardController as SuperadminDashboardController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\Admin\IssueController;
use App\Http\Controllers\Admin\JournalController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\OrderFormController;
use App\Http\Controllers\Admin\OrderTaskBoardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\RolePermisionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ReviewerController;
use App\Http\Controllers\Admin\ReviewerApplicationController;
use App\Http\Controllers\Admin\SendEmailController;
use App\Http\Controllers\Admin\SubmissionReviewerNoteController;
use App\Http\Controllers\VersionUpdateController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Superadmin\EmailTemplateController;
use App\Http\Controllers\Superadmin\SettingController;
use App\Models\Language;
use Illuminate\Support\Facades\Route;
use phpseclib3\File\ASN1\Maps\Certificate;

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


Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('recent-open-order', [DashboardController::class, 'recentOpenOrder'])->name('recent-open-order');
Route::get('revenue-overview-chart-data', [DashboardController::class, 'revenueOverviewChartData'])->name('revenue-overview-chart-data');
Route::get('client-overview-chart-data', [DashboardController::class, 'clientOverviewChartData'])->name('client-overview-chart-data');

Route::group(['prefix' => 'editor', 'as' => 'editor.'], function () {
    Route::get('dashboard', [EditorDashboardController::class, 'index'])->name('dashboard');
});

Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    // setting start
    Route::get('application-settings', [SettingController::class, 'applicationSetting'])->name('application-settings');
    Route::post('application-settings-update', [SettingController::class, 'applicationSettingUpdate'])->name('application-settings.update');
    Route::get('configuration-settings', [SettingController::class, 'configurationSetting'])->name('configuration-settings');
    Route::get('configuration-settings/configure', [SettingController::class, 'configurationSettingConfigure'])->name('configuration-settings.configure');
    Route::get('configuration-settings/help', [SettingController::class, 'configurationSettingHelp'])->name('configuration-settings.help');
    Route::post('configuration-settings-update', [SettingController::class, 'configurationSettingUpdate'])->name('configuration-settings.update')->middleware('isDemo');
    Route::post('application-env-update', [SettingController::class, 'saveSetting'])->name('settings_env.update');
    Route::get('logo-settings', [SettingController::class, 'logoSettings'])->name('logo-settings');
    Route::get('color-settings', [SettingController::class, 'colorSettings'])->name('color-settings');
    Route::get('storage-settings', [SettingController::class, 'storageSetting'])->name('storage.index');
    Route::post('storage-settings', [SettingController::class, 'storageSettingsUpdate'])->name('storage.update');
    Route::get('maintenance-mode-changes', [SettingController::class, 'maintenanceMode'])->name('maintenance');
    Route::post('maintenance-mode-changes', [SettingController::class, 'maintenanceModeChange'])->name('maintenance.change');

    Route::get('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-test', [SettingController::class, 'mailTest'])->name('mail.test');
    // setting end

    Route::group(['prefix' => 'gateway', 'as' => 'gateway.'], function () {
        Route::get('/', [GatewayController::class, 'index'])->name('index');
        Route::post('store', [GatewayController::class, 'store'])->name('store')->middleware('isDemo');
        Route::get('edit/{id}', [GatewayController::class, 'edit'])->name('edit')->middleware('isDemo');
        Route::get('get-info', [GatewayController::class, 'getInfo'])->name('get.info');
        Route::get('get-currency-by-gateway', [GatewayController::class, 'getCurrencyByGateway'])->name('get.currency');
        Route::get('syncs', [GatewayController::class, 'syncs'])->name('syncs');

    });

    Route::group(['prefix' => 'language', 'as' => 'languages.'], function () {
        Route::get('/', [LanguageController::class, 'index'])->name('index');
        Route::post('store', [LanguageController::class, 'store'])->name('store');
        Route::get('edit/{id}/{iso_code?}', [LanguageController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [LanguageController::class, 'update'])->name('update');
        Route::get('translate/{id}', [LanguageController::class, 'translateLanguage'])->name('translate');
        Route::post('update-translate/{id}', [LanguageController::class, 'updateTranslate'])->name('update.translate');
        Route::post('delete/{id}', [LanguageController::class, 'delete'])->name('delete');
        Route::post('update-language/{id}', [LanguageController::class, 'updateLanguage'])->name('update-language');
        Route::get('translate/{id}/{iso_code?}', [LanguageController::class, 'translateLanguage'])->name('translate');
        Route::get('update-translate/{id}', [LanguageController::class, 'updateTranslate'])->name('update.translate');
        Route::post('import', [LanguageController::class, 'import'])->name('import')->middleware('isDemo');
    });

    Route::group(['prefix' => 'currency', 'as' => 'currencies.'], function () {
        Route::get('', [CurrencyController::class, 'index'])->name('index');
        Route::post('currency', [CurrencyController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CurrencyController::class, 'edit'])->name('edit');
        Route::patch('update/{id}', [CurrencyController::class, 'update'])->name('update');
        Route::post('delete/{id}', [CurrencyController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'role-permission', 'as' => 'role-permission.'], function () {
        Route::get('/', [RolePermisionController::class, 'list'])->name('list');
        Route::get('add-new', [RolePermisionController::class, 'addNew'])->name('add-new');
        Route::get('edit/{id}', [RolePermisionController::class, 'edit'])->name('edit');
        Route::post('store', [RolePermisionController::class, 'store'])->name('store');
        Route::get('details/{id}', [RolePermisionController::class, 'details'])->name('details');
        Route::post('delete/{id}', [RolePermisionController::class, 'delete'])->name('delete');
        Route::get('permission/{id}', [RolePermisionController::class, 'permission'])->name('permission');
        Route::post('permission-update', [RolePermisionController::class, 'permissionUpdate'])->name('permission-update');
    });

    // designation
    Route::group(['prefix' => 'designation', 'as' => 'designation.'], function () {
        Route::get('/', [DesignationController::class, 'index'])->name('index');
        Route::get('add', [DesignationController::class, 'add'])->name('add');
        Route::post('store', [DesignationController::class, 'store'])->name('store');
        Route::get('edit/{id}', [DesignationController::class, 'edit'])->name('edit');
        Route::get('delete/{id}', [DesignationController::class, 'delete'])->name('delete');
    });

   // activity log
    Route::group(['prefix' => 'activity-log', 'as' => 'activity-log.'], function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    });

    // coupon
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('add', [CouponController::class, 'add'])->name('add');
        Route::post('store', [CouponController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CouponController::class, 'edit'])->name('edit');
        Route::get('delete/{id}', [CouponController::class, 'delete'])->name('delete');
    });

    Route::get('email-template', [EmailTemplateController::class, 'emailTemplate'])->name('email-template');
    Route::get('email-template-config', [EmailTemplateController::class, 'emailTemplateConfig'])->name('email.template.config');
    Route::post('email-template-config-update', [EmailTemplateController::class, 'emailTemplateConfigUpdate'])->name('email.template.config.update');


    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('update', [ProfileController::class, 'update'])->name('update')->middleware('isDemo');
        Route::get('password', [ProfileController::class, 'password'])->name('password');
        Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('password.update')->middleware('isDemo');
    });
});

Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
    Route::get('notification-mark-all-as-read', [NotificationController::class, 'notificationMarkAllAsRead'])->name('notification-mark-all-as-read');
    Route::get('view/{id}', [NotificationController::class, 'notificationView'])->name('view');
    Route::get('notification-mark-as-read/{id}', [NotificationController::class, 'notificationMarkAsRead'])->name('notification-mark-as-read');
});

Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
    Route::get('/', [ServiceController::class, 'list'])->name('list');
    Route::get('add-new', [ServiceController::class, 'addNew'])->name('add-new');
    Route::get('edit/{id}', [ServiceController::class, 'edit'])->name('edit');
    Route::post('store', [ServiceController::class, 'store'])->name('store');
    Route::get('details/{id}', [ServiceController::class, 'details'])->name('details');
    Route::get('delete', [ServiceController::class, 'delete'])->name('delete');
    Route::get('search', [ServiceController::class, 'search'])->name('search');
});

Route::group(['prefix' => 'journals', 'as' => 'journals.'], function () {
    Route::get('/', [JournalController::class, 'list'])->name('list');
    Route::get('add-new', [JournalController::class, 'addNew'])->name('add-new');
    Route::get('edit/{id}', [JournalController::class, 'edit'])->name('edit');
    Route::post('store', [JournalController::class, 'store'])->name('store');
    //Route::get('delete', [JournalController::class, 'delete'])->name('delete');
    Route::get('search', [JournalController::class, 'search'])->name('search');

    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('/', [JournalController::class, 'category_list'])->name('list');
        Route::get('add', [JournalController::class, 'categoryAddNew'])->name('add-new');
        Route::post('store', [JournalController::class, 'categoryStore'])->name('store');
        Route::get('edit/{id}', [JournalController::class, 'categoryEdit'])->name('edit');
        //Route::get('delete/{id}', [JournalController::class, 'category_delete'])->name('delete');
        Route::get('search', [JournalController::class, 'category_search'])->name('search');
     });
});

// Issues Management (Task 19-22)
Route::group(['prefix' => 'issues', 'as' => 'issues.'], function () {
    Route::get('/journal-issues', [IssueController::class, 'journalIssues'])->name('journal-issues');
    Route::get('/', [IssueController::class, 'index'])->name('index');
    Route::get('/create', [IssueController::class, 'create'])->name('create');
    Route::post('/store', [IssueController::class, 'store'])->name('store');
    Route::get('/get-by-journal', [IssueController::class, 'getByJournal'])->name('get-by-journal');
    Route::get('/{id}', [IssueController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [IssueController::class, 'edit'])->name('edit');
    Route::post('/{id}/update', [IssueController::class, 'update'])->name('update');
    Route::post('/{id}/delete', [IssueController::class, 'destroy'])->name('destroy');
});

Route::group(['prefix' => 'team-member', 'as' => 'team-member.'], function () {
    Route::get('/', [TeamMemberController::class, 'index'])->name('index');
    Route::get('add', [TeamMemberController::class, 'add'])->name('add');
    Route::post('store', [TeamMemberController::class, 'store'])->name('store');
    Route::get('edit/{id}', [TeamMemberController::class, 'edit'])->name('edit');
    Route::get('delete/{id}', [TeamMemberController::class, 'delete'])->name('delete');
});

Route::group(['prefix' => 'send-email', 'as' => 'send-email.'], function () {
    Route::get('/', [SendEmailController::class, 'index'])->name('index');
    Route::get('/template-list', [SendEmailController::class, 'template_list'])->name('template.list');
    Route::get('/template-view', [SendEmailController::class, 'template_details'])->name('template.details');
    Route::post('/template-store', [SendEmailController::class, 'template_store'])->name('template.store');
    Route::get('/template-edit', [SendEmailController::class, 'template_edit'])->name('template.edit');
    Route::get('/template-update', [SendEmailController::class, 'template_update'])->name('template.update');
    Route::post('/template-delete/{id}', [SendEmailController::class, 'template_delete'])->name('template.delete');

    Route::get('/get-email-template', [SendEmailController::class, 'get_email_template'])->name('get.email.template');
    Route::post('/send', [SendEmailController::class, 'send'])->name('send');
    Route::get('/history', [SendEmailController::class, 'history'])->name('history');
});

Route::group(['prefix' => 'submitted-books', 'as' => 'submitted-books.'], function () {
   Route::get('/list', [BookSubmissionController::class, 'list'])->name('list');
   Route::get('/show/{id}', [BookSubmissionController::class, 'show'])->name('show');
});

// Research Submission Management (Authors Form Submissions)
Route::group(['prefix' => 'research-submission', 'as' => 'research-submission.'], function () {
   Route::get('/', [ResearchSubmissionController::class, 'index'])->name('index');
   Route::get('/data', [ResearchSubmissionController::class, 'getData'])->name('data');
   Route::get('/show/{id}', [ResearchSubmissionController::class, 'show'])->name('show');
   Route::get('/download-docx/{id}', [ResearchSubmissionController::class, 'downloadDocx'])->name('download-docx');
   Route::post('/approve/{id}', [ResearchSubmissionController::class, 'approve'])->name('approve');
   Route::post('/reject/{id}', [ResearchSubmissionController::class, 'reject'])->name('reject');
});

Route::group(['prefix' => 'order-form', 'as' => 'order-form.'], function () {
    Route::get('/', [OrderFormController::class, 'index'])->name('index');
    Route::get('add', [OrderFormController::class, 'add'])->name('add');
    Route::post('store', [OrderFormController::class, 'store'])->name('store');
    Route::get('edit/{id}', [OrderFormController::class, 'edit'])->name('edit');
    Route::get('delete/{id}', [OrderFormController::class, 'delete'])->name('delete');
});

//client
Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
    Route::get('/', [ClientController::class, 'list'])->name('list');
    Route::get('client-add', [ClientController::class, 'add'])->name('add-list');
    Route::post('client-store', [ClientController::class, 'store'])->name('store');
    Route::post('client-delete/{id}', [ClientController::class, 'delete'])->name('delete');
    Route::get('edit/{id}', [ClientController::class, 'edit'])->name('edit');
    Route::get('details/{id}', [ClientController::class, 'details'])->name('details');
    Route::get('invoice/{id}', [ClientController::class, 'clientInvoiceHistory'])->name('invoice');
    Route::get('activity-log/{id}', [ClientController::class, 'clientActivityHistory'])->name('activity-log-history');
    Route::post('/update-status/{id}', [ClientController::class, 'updateStatus'])->name('update-status');

});

//reviewer
Route::group(['prefix' => 'reviewer', 'as' => 'reviewer.'], function () {
    Route::get('/', [ReviewerController::class, 'list'])->name('list');
    Route::get('reviewer-add', [ReviewerController::class, 'add'])->name('add-list');
    Route::post('reviewer-store', [ReviewerController::class, 'store'])->name('store');
    Route::post('reviewer-delete/{id}', [ReviewerController::class, 'delete'])->name('delete');
    Route::get('edit/{id}', [ReviewerController::class, 'edit'])->name('edit');
    Route::get('details/{id}', [ReviewerController::class, 'details'])->name('details');
    // Route::get('invoice/{id}', [ReviewerController::class, 'clientInvoiceHistory'])->name('invoice');
    // Route::get('activity-log/{id}', [ReviewerController::class, 'clientActivityHistory'])->name('activity-log-history');
    Route::post('/update-status/{id}', [ReviewerController::class, 'updateStatus'])->name('update-status');
});

// Reviewer Applications Management
Route::group(['prefix' => 'reviewer-application', 'as' => 'reviewer-application.'], function () {
    Route::get('/', [ReviewerApplicationController::class, 'index'])->name('index');
    Route::get('/show/{id}', [ReviewerApplicationController::class, 'show'])->name('show');
    Route::post('/approve/{id}', [ReviewerApplicationController::class, 'approve'])->name('approve');
    Route::post('/reject/{id}', [ReviewerApplicationController::class, 'reject'])->name('reject');
});

// Smart Reviewer Matching
Route::get('/reviewer-matching/recommendations', [\App\Http\Controllers\Admin\ReviewerMatchingController::class, 'getRecommendations'])->name('reviewer-matching.recommendations');

// Final Metadata Review (Task 9)
Route::group(['prefix' => 'submissions', 'as' => 'submissions.'], function () {
    Route::get('/final-acceptance-certificates', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'index'])->name('final-acceptance-certificates.index');
    Route::get('/final-metadata/{submission_id}', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'review'])->name('final-metadata.review');
    Route::post('/final-metadata/review-action', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'reviewAction'])->name('final-metadata.review-action');
    // API route to fetch issues for a journal
    Route::get('/journal/{journal_id}/issues', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'getJournalIssues'])->name('journal.issues');
    Route::post('/final-acceptance-certificate/generate/{submission_id}', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'generateCertificate'])->name('final-acceptance-certificate.generate');
    Route::get('/final-acceptance-certificate/{submission_id}', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'downloadCertificate'])->name('final-acceptance-certificate.download');
    Route::get('/final-acceptance-certificate/{submission_id}/edit', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'editCertificate'])->name('final-acceptance-certificate.edit');
    Route::post('/final-acceptance-certificate/{submission_id}/update', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'updateCertificate'])->name('final-acceptance-certificate.update');
    Route::post('/final-acceptance-certificate/{submission_id}/resend', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'resendCertificate'])->name('final-acceptance-certificate.resend');
    Route::post('/final-acceptance-certificate/{submission_id}/send-reminder', [\App\Http\Controllers\Admin\FinalMetadataController::class, 'sendMetadataReminder'])->name('final-acceptance-certificate.send-reminder');
});

// Proofreading (Task 12-13)
Route::group(['prefix' => 'proofreading', 'as' => 'proofreading.'], function () {
    Route::get('/', [\App\Http\Controllers\Admin\ProofreadingController::class, 'index'])->name('index');
    Route::get('/list/{submission_id}', [\App\Http\Controllers\Admin\ProofreadingController::class, 'listProofs'])->name('list');
    Route::post('/upload/{submission_id}', [\App\Http\Controllers\Admin\ProofreadingController::class, 'uploadProof'])->name('upload');
    Route::post('/assign-reviewer/{proof_id}', [\App\Http\Controllers\Admin\ProofreadingController::class, 'assignReviewer'])->name('assign-reviewer');
    Route::get('/review/{proof_id}', [\App\Http\Controllers\Admin\ProofreadingController::class, 'reviewProofPage'])->name('review');
    Route::post('/review/{proof_id}', [\App\Http\Controllers\Admin\ProofreadingController::class, 'reviewProof'])->name('review.submit');
});

// Galley (Task 14-15)
Route::group(['prefix' => 'galley', 'as' => 'galley.'], function () {
    Route::get('/', [\App\Http\Controllers\Admin\GalleyController::class, 'index'])->name('index');
    Route::get('/list/{submission_id}', [\App\Http\Controllers\Admin\GalleyController::class, 'listGalleys'])->name('list');
    Route::post('/upload/{submission_id}', [\App\Http\Controllers\Admin\GalleyController::class, 'uploadGalley'])->name('upload');
});

// OJS Integration (Task 16-18)
Route::group(['prefix' => 'ojs', 'as' => 'ojs.'], function () {
    Route::get('/', [\App\Http\Controllers\Admin\OjsIntegrationController::class, 'index'])->name('index');
    Route::get('/quicksubmit-data/{submission_id}', [\App\Http\Controllers\Admin\OjsIntegrationController::class, 'quickSubmitData'])->name('quicksubmit-data');
    Route::post('/update-publication/{submission_id}', [\App\Http\Controllers\Admin\OjsIntegrationController::class, 'updatePublication'])->name('update-publication');
    Route::post('/auto-submit/{submission_id}', [\App\Http\Controllers\Admin\OjsIntegrationController::class, 'autoSubmit'])->name('auto-submit');
});

// Submission History (Task 25-26)
Route::group(['prefix' => 'submission-history', 'as' => 'submission-history.'], function () {
    Route::get('/', [\App\Http\Controllers\Admin\SubmissionHistoryController::class, 'index'])->name('index');
});

//Certificate
Route::group(['prefix' => 'certificate', 'as' => 'certificate.'], function () {

    //primary certificate
     Route::group(['prefix' => 'primary', 'as' => 'primary.'], function () {
        Route::get('/', [CertificateController::class, 'primary'])->name('index');
     });

     //final certificate
     Route::group(['prefix' => 'final', 'as' => 'final.'], function () {
        Route::get('/', [CertificateController::class, 'finalCertificateList'])->name('list');
        Route::get('add', [CertificateController::class, 'finalCertificateAdd'])->name('add');
        Route::get('order-details', [CertificateController::class, 'finalCertificateOrderDetails'])->name('order-details');
        Route::post('store', [CertificateController::class, 'finalCertificateStore'])->name('store');
        Route::get('edit/{id}', [CertificateController::class, 'finalCertificateEdit'])->name('edit');
        Route::get('/print/{id}', [CertificateController::class, 'finalCertificatePrint'])->name('print');
        Route::get('/send/{id}', [CertificateController::class, 'finalCertificateSend'])->name('send');
     });

     //reviewer certificate
     Route::group(['prefix' => 'reviewer', 'as' => 'reviewer.'], function () {
        Route::get('/', [CertificateController::class, 'reviewerCertificateList'])->name('list');
        Route::get('add', [CertificateController::class, 'reviewerCertificateAdd'])->name('add');
        Route::get('order-details', [CertificateController::class, 'reviewerCertificateOrderDetails'])->name('order-details');
        Route::post('store', [CertificateController::class, 'reviewerCertificateStore'])->name('store');
        Route::get('edit/{id}', [CertificateController::class, 'reviewerCertificateEdit'])->name('edit');
        Route::get('/print/{id}', [CertificateController::class, 'reviewerCertificatePrint'])->name('print');
        Route::get('/send/{id}', [CertificateController::class, 'reviewerCertificateSend'])->name('send');
     });


});




Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
    Route::get('/', [TicketController::class, 'list'])->name('list');
    Route::get('add-new', [TicketController::class, 'addNew'])->name('add-new');
    Route::get('edit/{id}', [TicketController::class, 'edit'])->name('edit');
    Route::post('store', [TicketController::class, 'store'])->name('store');
    Route::get('details/{id}', [TicketController::class, 'details'])->name('details');
    Route::post('delete/{id}', [TicketController::class, 'delete'])->name('delete');
    Route::get('assign-member', [TicketController::class, 'assignMember'])->name('assign-member');
    Route::get('priority-change/{ticket_id}/{priority}', [TicketController::class, 'priorityChange'])->name('priority-change');
    Route::post('conversations-store', [TicketController::class, 'conversationsStore'])->name('conversations.store');
    Route::post('conversations-edit', [TicketController::class, 'conversationsEdit'])->name('conversations.edit');
    Route::post('conversations-delete/{id}', [TicketController::class, 'conversationsDelete'])->name('conversations.delete');
    Route::get('status-change', [TicketController::class, 'statusChange'])->name('status.change');
});

//Client Order Submission Reviewer notes
Route::group(['prefix' => 'submission-reviewer-notes', 'as' => 'submission-reviewer-notes.'], function () {
    // Route::get('/', [SubmissionReviewerNoteController::class, 'list'])->name('list');
    Route::get('add-new/{id}', [SubmissionReviewerNoteController::class, 'addNew'])->name('add-new');
    Route::get('edit/{id}', [SubmissionReviewerNoteController::class, 'edit'])->name('edit');
    Route::post('store', [SubmissionReviewerNoteController::class, 'store'])->name('store');
    Route::get('details/{id}', [SubmissionReviewerNoteController::class, 'details'])->name('details');
    Route::post('delete/{id}', [SubmissionReviewerNoteController::class, 'delete'])->name('delete');
    Route::post('conversations-store', [SubmissionReviewerNoteController::class, 'conversationsStore'])->name('conversations.store');
    // Route::post('conversations-edit', [SubmissionReviewerNoteController::class, 'conversationsEdit'])->name('conversations.edit');
    Route::post('conversations-delete/{id}', [SubmissionReviewerNoteController::class, 'conversationsDelete'])->name('conversations.delete');
});

//client invoice
Route::group(['prefix' => 'client-invoice', 'as' => 'client-invoice.'], function () {
    Route::get('/', [ClientInvoiceController::class, 'list'])->name('list');
    Route::get('add-new', [ClientInvoiceController::class, 'addNew'])->name('add-new');
    Route::post('store', [ClientInvoiceController::class, 'store'])->name('store');
    Route::post('delete/{id}', [ClientInvoiceController::class, 'delete'])->name('delete');
    Route::get('all-service', [ClientInvoiceController::class, 'getService'])->name('all-service');
    Route::get('details/{id}', [ClientInvoiceController::class, 'details'])->name('details');
    Route::get('edit/{id}', [ClientInvoiceController::class, 'edit'])->name('edit');
    Route::get('order', [ClientInvoiceController::class, 'getOrder'])->name('order');
    Route::get('print/{id}', [ClientInvoiceController::class, 'invoicePrint'])->name('print');
    Route::get('payment-edit/{id}', [ClientInvoiceController::class, 'paymentEdit'])->name('payment-edit');
    Route::post('payment-status-change/{id}', [ClientInvoiceController::class, 'paymentStatusChange'])->name('payment_status_change');
});

//client-order-info
Route::group(['prefix' => 'client-orders', 'as' => 'client-orders.'], function () {
    Route::get('/', [ClientOrderController::class, 'list'])->name('list');
    Route::get('add', [ClientOrderController::class, 'add'])->name('add');
    Route::get('all-service', [ClientOrderController::class, 'getService'])->name('all-service');
    Route::post('store', [ClientOrderController::class, 'store'])->name('store');
    Route::get('edit/{id}', [ClientOrderController::class, 'edit'])->name('edit');
    Route::post('delete/{id}', [ClientOrderController::class, 'delete'])->name('delete');
    Route::get('details/{id}', [ClientOrderController::class, 'details'])->name('details');
    Route::post('conversation', [ClientOrderController::class, 'conversationStore'])->name('conversation.store');
    Route::get('status-change/{order_id}/{status}', [ClientOrderController::class, 'statusChange'])->name('status.change');
    Route::post('submission-status-change', [ClientOrderController::class, 'submissionStatusChange'])->name('submission.status.change');
    Route::get('assign-member', [ClientOrderController::class, 'assignMember'])->name('assign.member');
    Route::post('note-store', [ClientOrderController::class, 'noteStore'])->name('note.store');
    Route::post('note-delete/{id}', [ClientOrderController::class, 'noteDelete'])->name('note.delete');
    //submission full view data
    Route::get('/{id}/fullview', [ClientOrderController::class, 'fullview'])->name('fullview');

    Route::group(['prefix' => 'task-board', 'as' => 'task-board.'], function () {
        Route::get('/{order_id}', [OrderTaskBoardController::class, 'list'])->name('index');
        Route::post('/{order_id}/{id?}', [OrderTaskBoardController::class, 'store'])->where(['order_id' => '[0-9]+', 'id' => '[0-9]*'])->name('store');
        Route::post('/{order_id}/update-task-status', [OrderTaskBoardController::class, 'updateStatus'])->name('update_status');
        Route::get('/{order_id}/edit/{id}', [OrderTaskBoardController::class, 'edit'])->name('edit');
        Route::post('/{order_id}/delete/{id}', [OrderTaskBoardController::class, 'delete'])->name('delete');
        Route::get('/{order_id}/view/{id}', [OrderTaskBoardController::class, 'view'])->name('view');
        Route::post('/{order_id}/delete-attachment/{id}/{attachment_id}', [OrderTaskBoardController::class, 'deleteAttachment'])->name('delete-attachment');
        // Editor Decision Routes
        Route::post('/editor-decision/{submission_id}', [OrderTaskBoardController::class, 'editorDecision'])->name('editor-decision');
        Route::post('/request-revision/{submission_id}', [OrderTaskBoardController::class, 'requestRevision'])->name('request-revision');
        Route::post('/complete-stage/{submission_id}', [OrderTaskBoardController::class, 'completeStage'])->name('complete-stage');
        Route::post('{order_id}/change-progress/{id}', [OrderTaskBoardController::class, 'changeProgress'])->name('change_progress');

        Route::group(['prefix' => 'conversation', 'as' => 'conversation.'], function () {
            Route::post('{order_id}/{id}', [OrderTaskBoardController::class, 'conversationStore'])->name('store');
        });
    });
});

// quotation route
Route::group(['prefix' => 'quotation', 'as' => 'quotation.'], function () {
    Route::get('/', [QuotationController::class, 'list'])->name('list');
    Route::post('store', [QuotationController::class, 'store'])->name('store');
    Route::get('add', [QuotationController::class, 'add'])->name('add');
    Route::get('edit/{id}', [QuotationController::class, 'edit'])->name('edit');
    Route::get('all-service', [QuotationController::class, 'getService'])->name('all-service');
    Route::get('delete/{id}', [QuotationController::class, 'delete'])->name('delete');
    Route::get('details/{id}', [QuotationController::class, 'details'])->name('details');
    Route::get('print/{id}', [QuotationController::class, 'quotationPrint'])->name('print');
    Route::get('send/{id}', [QuotationController::class, 'quotationSend'])->name('send');
});


Route::get('version-update', [VersionUpdateController::class, 'versionFileUpdate'])->name('file-version-update');
Route::post('version-update', [VersionUpdateController::class, 'versionFileUpdateStore'])->name('file-version-update-store');
Route::get('version-update-execute', [VersionUpdateController::class, 'versionUpdateExecute'])->name('file-version-update-execute');
Route::get('version-delete', [VersionUpdateController::class, 'versionFileUpdateDelete'])->name('file-version-delete');

Route::get('script-' . now()->format('Ymd'), [VersionUpdateController::class, 'pathFile'])->name('script-file');
Route::post('script-file', [VersionUpdateController::class, 'downloadPathFile'])->name('load-script-file');
Route::post('store-script-file', [VersionUpdateController::class, 'storePathFile'])->name('store-script-file');

Route::group(['prefix' => 'addon', 'as' => 'addon.'], function () {
    Route::get('details/{code}', [AddonUpdateController::class, 'addonSaasDetails'])->name('details')->withoutMiddleware(['addon.update']);
    Route::post('store', [AddonUpdateController::class, 'addonSaasFileStore'])->name('store')->withoutMiddleware(['addon.update']);
    Route::post('execute', [AddonUpdateController::class, 'addonSaasFileExecute'])->name('execute')->withoutMiddleware(['addon.update']);
    Route::get('delete/{code}', [AddonUpdateController::class, 'addonSaasFileDelete'])->name('delete')->withoutMiddleware(['addon.update']);
});

Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
    Route::get('/', [FileManagerController::class, 'show'])->name('unisharp.lfm.show');
});

Route::get('kanban/', function () {
    $data['pageTitle'] = 'Board';
    return view('admin.orders.task-board.list', $data);
});

//Instructions
Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
    Route::get('/list', [PageController::class, 'list'])->name('list');
    Route::get('add', [PageController::class, 'add'])->name('add');
    Route::post('store', [PageController::class, 'store'])->name('store');
    Route::get('edit/{id}', [PageController::class, 'edit'])->name('edit');
    Route::get('/{slug}', [PageController::class, 'view'])->name('view');
    Route::post('delete/{id}', [PageController::class, 'delete'])->name('delete');
});
Route::get('journal/workflow', [SuperadminDashboardController::class, 'journalWorkflow'])->name('journal.workflow');
Route::post('journal/workflow/store-paper', [SuperadminDashboardController::class, 'storePaper'])->name('journal.workflow.store');
Route::post('journal/workflow/update-status', [SuperadminDashboardController::class, 'updateTaskStatus'])->name('journal.workflow.update');
