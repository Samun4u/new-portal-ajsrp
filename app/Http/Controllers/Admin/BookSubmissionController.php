<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\ClientBookServices;
use App\Models\BookSubmission;
use App\Traits\ResponseTrait;

class BookSubmissionController extends Controller
{
    use ResponseTrait;

    public $clientBookService;

    public function __construct()
    {
        $this->clientBookService = new ClientBookServices();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->clientBookService->getClientBookListData($request);
        }
        $data['pageTitle'] = __('Book list');
        $data['activeSubmittedBooks'] = 'active';
        $data['booksCount'] = $this->clientBookService->bookCount();
        return view('admin.book_submission.list', $data);
    }

    public function show($id)
    {
        $data['pageTitle'] = __('Submit Your Book');
        $data['bookSubmission'] = BookSubmission::findOrFail($id);
        
        return view('admin.book_submission.show', $data);
    }
}
