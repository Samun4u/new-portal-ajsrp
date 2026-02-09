<?php

use App\Http\Controllers\Admin\ClientOrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\ClientInvoiceController;
use App\Http\Controllers\Admin\OrderTaskBoardController;
use App\Http\Controllers\User\ApplicationController;
use App\Http\Controllers\User\BookSubmissionController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ServiceController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ReviewerReviewController;
use App\Http\Controllers\User\EBMController;
use App\Http\Controllers\User\InstructionController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\InvoiceController;
use App\Http\Controllers\User\PageController;
use App\Http\Controllers\User\SubmissionController;
use App\Http\Controllers\User\SubmissionReviewerNoteController;

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
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('order-summery', [DashboardController::class, 'orderSummery'])->name('order-summery');

Route::group(['prefix' => 'reviewer', 'as' => 'reviewer.'], function () {
    Route::get('reviews/{review}', [ReviewerReviewController::class, 'show'])->name('reviews.show');
    Route::post('reviews/{review}/autosave', [ReviewerReviewController::class, 'autosave'])->name('reviews.autosave');
    Route::post('reviews/{review}/submit', [ReviewerReviewController::class, 'submit'])->name('reviews.submit');
    Route::post('reviews/{review}/revision-package', [ReviewerReviewController::class, 'submitRevisionPackage'])->name('reviews.revision');
});

//notification  route start
Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
    Route::get('all', [NotificationController::class, 'allNotification'])->name('all');
    Route::get('mark-as-read', [NotificationController::class, 'notificationMarkAsRead'])->name('mark-as-read');
    Route::get('view/{id}', [NotificationController::class, 'notificationView'])->name('view');
    Route::get('delete/{id}', [NotificationController::class, 'notificationDelete'])->name('delete');

    Route::get('notification-mark-all-as-read', [NotificationController::class, 'notificationMarkAllAsRead'])->name('notification-mark-all-as-read');
    Route::get('notification-mark-as-read/{id}', [NotificationController::class, 'notificationMarkAsRead'])->name('notification-mark-as-read');
});
// notification route end

//invoice route start
Route::group(['prefix' => 'invoice', 'as' => 'invoice.'], function () {
    Route::get('list/{plan_id?}', [InvoiceController::class, 'list'])->name('list');
    Route::get('view', [InvoiceController::class, 'invoiceView'])->name('view');
    Route::get('get-plan-data', [InvoiceController::class, 'getPlanData'])->name('get.plan.data');
    Route::get('print/{id}', [InvoiceController::class, 'invoicePrint'])->name('print');
    Route::get('download/{id}', [InvoiceController::class, 'invoiceDownload'])->name('download');
});
//invoice route end

// order route start
Route::group(['prefix' => 'submissions', 'as' => 'orders.'], function () {
//    Route::get('/', [OrderController::class, 'index'])->name('payment.status');
//    Route::get('payment-show/{id}', [OrderController::class, 'paymentShow'])->name('payment.show');
//    Route::get('payment-edit/{id}', [OrderController::class, 'paymentEdit'])->name('payment.edit');
//    Route::post('payment-status-update', [OrderController::class, 'paymentUpdate'])->name('payment.status.update');
//    Route::get('sales', [OrderController::class, 'sales'])->name('sales');
    Route::get('/', [OrderController::class, 'list'])->name('list');
    Route::get('details/{id}', [OrderController::class, 'details'])->name('details');
    Route::post('conversation', [OrderController::class, 'conversationStore'])->name('conversation.store');

    //submission full view data
    Route::get('/{id}/fullview', [ClientOrderController::class, 'fullview'])->name('fullview');
    Route::get('/{order_id}/dashboard', [SubmissionController::class, 'authorDashboard'])->name('dashboard');
    Route::get('/{order_id}/reviews-summary', [SubmissionController::class, 'authorReviewsSummary'])->name('reviews.summary');

    Route::group(['prefix' => 'task-board', 'as' => 'task-board.'], function () {
        Route::get('/{order_id}', [OrderTaskBoardController::class, 'list'])->name('index');
        Route::post('/upload/file', [OrderTaskBoardController::class, 'uploadfile'])->name('uploadfile');
        Route::get('/{order_id}/view/{id}', [OrderTaskBoardController::class, 'view'])->name('view');
        Route::get('/{order_id}/primary-certificate', [OrderTaskBoardController::class, 'primary_certificate'])->name('primary.certificate');
        Route::get('/{order_id}/final-certificate', [OrderTaskBoardController::class, 'final_certificate'])->name('final.certificate');
        Route::get('/{order_id}/reviewer-certificate', [OrderTaskBoardController::class, 'reviewer_certificate'])->name('reviewer.certificate');
        Route::group(['prefix' => 'conversation', 'as' => 'conversation.'], function () {
            Route::post('{order_id}/{id}', [OrderTaskBoardController::class, 'conversationStore'])->name('store');
        });
    });

    Route::get('/{order_id}/revision', [SubmissionController::class, 'revisionForm'])->name('revision.form');
    Route::post('/{order_id}/revision', [SubmissionController::class, 'revisionSubmit'])->name('revision.submit');

    Route::get('send', [OrderController::class, 'send_form'])->name('send.form');

    //reviewer order route start =>
    Route::get('/assigned-reviews', [OrderController::class, 'reviewer_assigned_order_list'])->name('reviewer.assigned.list');
    Route::get('/my-submissions', [OrderController::class, 'reviewer_submission_list'])->name('reviewer.submission.list');
    Route::get('/assigned-order-status-change/{order_submission_id}/{id}', [OrderController::class, 'reviewer_assigned_order_status_change'])->name('reviewer.assigned.order.status.change');

    //reviewer order route end =>
});
// order route end

Route::group(['prefix' => 'submit-your-book', 'as' => 'submit-your-book.'], function () {
    Route::get('/', [BookSubmissionController::class, 'index'])->name('index');
    Route::post('/store', [BookSubmissionController::class, 'store'])->name('store');
    Route::get('/list', [BookSubmissionController::class, 'list'])->name('list');
    Route::get('/show/{id}', [BookSubmissionController::class, 'show'])->name('show');
});

Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
    Route::get('/', [ServiceController::class, 'list'])->name('list');
    Route::get('details/{id}', [ServiceController::class, 'details'])->name('details');
    Route::get('search', [ServiceController::class, 'search'])->name('search');
});

// client-profile
Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('update', [ProfileController::class, 'update'])->name('update')->middleware('isDemo');
    Route::get('password', [ProfileController::class, 'password'])->name('password');
    Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('password.update')->middleware('isDemo');
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



Route::post('gateway-list', [CheckoutController::class, 'gatewayList'])->name('gateway.list');
Route::get('currency-list', [CheckoutController::class, 'currencyList'])->name('currency.list');
Route::get('apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');
Route::post('checkout/order', [CheckoutController::class, 'checkoutOrderPlace'])->name('checkout.order.place');
Route::post('checkout/orderempt', [CheckoutController::class, 'checkoutOrderWithoutpaiement'])->name('checkout.order.withoutpaiement');


//client-order-info
Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {

});

// invoice route
Route::group(['prefix' => 'client-invoice', 'as' => 'client-invoice.'], function () {
    Route::get('/', [ClientInvoiceController::class, 'list'])->name('list');
    Route::get('details/{id}', [ClientInvoiceController::class, 'details'])->name('details');
    Route::get('print/{id}', [ClientInvoiceController::class, 'invoicePrint'])->name('print');
});

//submission
Route::group(['prefix' => 'submission', 'as' => 'submission.'], function () {
    Route::get('/', [SubmissionController::class, 'index'])->name('index');

    Route::get('/select-a-journal/{by}/{action?}/{id?}', [SubmissionController::class, 'select_a_journal'])
    ->where('action', 'update')
    ->where('id', '[a-zA-Z0-9\-]+')
    ->name('select-a-journal');

    Route::post('/select-a-journal-save', [SubmissionController::class, 'select_a_journal_save'])->name('select-a-journal.save');


    Route::get('/article-information/{action?}/{id?}', [SubmissionController::class, 'article_information'])->name('article.information');
    Route::post('/article-information-save', [SubmissionController::class, 'article_information_save'])->name('article.information.save');

    Route::get('/upload-files/{id}', [SubmissionController::class, 'upload_files'])->name('upload.files');
    Route::post('/upload-files-save', [SubmissionController::class, 'upload_files_save'])->name('upload.files.save');


    Route::get('/add-authors/{id}', [SubmissionController::class, 'add_authors'])->name('add.authors');
    Route::post('/add-authors-save', [SubmissionController::class, 'add_authors_save'])->name('add.authors.save');

    Route::get('/declarations/{id}', [SubmissionController::class, 'declarations'])->name('declarations');
    Route::post('/declarations-save', [SubmissionController::class, 'declarations_save'])->name('declarations.save');

    Route::group(['prefix' => 'add-reviewers', 'as' => 'add-reviewers.'], function () {
        Route::get('/{id}', [SubmissionController::class, 'add_reviewers_index'])->name('index');
        Route::post('/save', [SubmissionController::class, 'add_reviewers_save'])->name('save');

        Route::get('/from-references/{id}', [SubmissionController::class, 'add_reviewers_from_references'])->name('from-references');
        Route::post('/from-references-save', [SubmissionController::class, 'add_reviewers_from_references_save'])->name('from-references.save');

        Route::get('/opposed/{id}', [SubmissionController::class, 'add_reviewers_opposed'])->name('opposed');
        Route::post('/opposed-save', [SubmissionController::class, 'add_reviewers_opposed_save'])->name('opposed.save');
    });

    Route::get('/review/{id}', [SubmissionController::class, 'review'])->name('review');
    Route::post('/review-save', [SubmissionController::class, 'review_save'])->name('review.save');

    Route::get('/success-review/{id}', [SubmissionController::class, 'success_review'])->name('success.review');

    // Final Metadata Form (Task 7 & 8)
    Route::get('/final-metadata/{submission_id}', [SubmissionController::class, 'finalMetadataForm'])->name('final-metadata.form');
    Route::post('/final-metadata/store', [SubmissionController::class, 'finalMetadataStore'])->name('final-metadata.store');
    Route::get('/final-acceptance-certificate/{submission_id}', [SubmissionController::class, 'downloadFinalAcceptanceCertificate'])->name('final-acceptance-certificate.download');

    // Proofreading (Task 12-13)
    Route::group(['prefix' => 'proofreading', 'as' => 'proofreading.'], function () {
        Route::get('/review/{proof_id}', [\App\Http\Controllers\User\ProofreadingController::class, 'review'])->name('review');
        Route::post('/approve/{proof_id}', [\App\Http\Controllers\User\ProofreadingController::class, 'approveProof'])->name('approve');
        Route::post('/request-corrections/{proof_id}', [\App\Http\Controllers\User\ProofreadingController::class, 'requestCorrections'])->name('request-corrections');
    });

    // Galley (Task 14-15)
    Route::group(['prefix' => 'galley', 'as' => 'galley.'], function () {
        Route::get('/review/{galley_id}', [\App\Http\Controllers\User\GalleyController::class, 'review'])->name('review');
        Route::post('/approve/{galley_id}', [\App\Http\Controllers\User\GalleyController::class, 'approveGalley'])->name('approve');
        Route::post('/request-corrections/{galley_id}', [\App\Http\Controllers\User\GalleyController::class, 'requestCorrections'])->name('request-corrections');
    });

    // Submission History (Task 25)
    Route::group(['prefix' => 'submission-history', 'as' => 'submission-history.'], function () {
        Route::get('/', [\App\Http\Controllers\User\SubmissionHistoryController::class, 'index'])->name('index');
        Route::get('/{order_id}', [\App\Http\Controllers\User\SubmissionHistoryController::class, 'show'])->name('show');
    });


    //post routes
    Route::post('/get-journals-by-subject', [SubmissionController::class, 'getJournalsBySubject'])->name('getJournalsBySubject');
    Route::post('/get-journals-by-letter', [SubmissionController::class, 'getJournalsByLetter'])->name('getJournalsByLetter');
    Route::get('/search-journals', [SubmissionController::class, 'searchJournals'])->name('searchJournals');
    Route::post('/delete-cover-letter', [SubmissionController::class, 'deleteCoverLetter'])->name('deleteCoverLetter');
    Route::post('/delete-supplementary', [SubmissionController::class, 'deleteSupplementary'])->name('deleteSupplementary');


});

//Join as Editorial Board Member
Route::group(['prefix' => 'join', 'as' => 'join.'], function () {
    Route::group(['prefix' => 'application', 'as' => 'application.'], function () {

        //Editorial Board Member
        Route::group(['prefix' => 'editorial-board-member', 'as' => 'editorial-board-member.'], function () {
            Route::get('/', [ApplicationController::class, 'editorial_board_member'])->name('index');
            Route::post('/save', [ApplicationController::class, 'editorial_board_member_save'])->name('save');
        });

        //Become a Reviewer
        Route::group(['prefix' => 'reviewer', 'as' => 'reviewer.'], function () {
            Route::get('/', [ApplicationController::class, 'become_a_reviewer'])->name('index');
            Route::post('/save', [ApplicationController::class, 'become_a_reviewer_save'])->name('save');
        });
    });
});

// //Instructions
// Route::get('/page/{slug}', [PageController::class, 'index'])->name('page.index');

//Instructions
Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
    Route::get('/list', [PageController::class, 'list'])->name('list');
    Route::get('/{slug}', [PageController::class, 'view'])->name('view');
});


