<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientBookServices;
use App\Models\BookSubmission;
use App\Models\FileManager;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookSubmissionNotification;

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
        $loggedUserID = auth()->user()->id;
        if ($request->ajax()) {
            return $this->clientBookService->getClientBookListData($request, $loggedUserID);
        }
        $data['pageTitle'] = __('Book list');
        $data['activeSubmittedBooks'] = 'active';
        $data['booksCount'] = $this->clientBookService->bookCount($loggedUserID);
        return view('user.book_submission.list', $data);
    }


    public function index()
    {
        $data['pageTitle'] = __('Submit Your Book');
        $data['activeSubmittedBooks'] = 'active';
        return view('user.book_submission.index', $data);
    }

    public function store(Request $request)
    {
         // Validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'author' => 'required|string|max:120',
            'genre' => 'nullable|string|max:100',
            'language' => 'nullable|string|in:English,Arabic,French,Spanish,German,Other',
            'year' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'email' => 'required|email',
            'summary' => 'nullable|string|max:2000',
            'bookFile' => 'required|file|mimes:pdf,epub,docx|max:102400', // 100MB in KB
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB
            'allowPublic' => 'sometimes|accepted',
        ], [
            'bookFile.max' => 'The book manuscript must not exceed 100 MB.',
            'coverImage.max' => 'The cover image must not exceed 2 MB.',
            'year.digits' => 'The publication year must be a valid 4-digit year.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            // Store book file
            // $bookFilePath = $request->file('bookFile')->store('book-submissions', 'public');
            $file = new FileManager();
            $bookFileUploaded = $file->upload('book-submissions', $request->bookFile);
            $bookFileId = $bookFileUploaded->id;
            
            // Store cover image if provided
            $coverImageFileId = null;
            if ($request->hasFile('coverImage')) {
                // $coverImagePath = $request->file('coverImage')->store('book-covers', 'public');
                $file = new FileManager();
                $coverImageUploaded = $file->upload('book-covers', $request->coverImage);
                $coverImageFileId = $coverImageUploaded->id;
            }

            // Create book submission record
            $bookSubmission = BookSubmission::create([
                'client_id' => auth()->user()->id,
                'title' => $request->title,
                'author' => $request->author,
                'genre' => $request->genre,
                'language' => $request->language,
                'publication_year' => $request->year,
                'email' => $request->email,
                'summary' => $request->summary,
                'book_file_id' => $bookFileId,
                'cover_image_file_id' => $coverImageFileId,
                'allow_public' => $request->has('allowPublic'),
            ]);

            // Send email notification to books@ajsrp.com 
            Mail::to('books@ajsrp.com')->send(new BookSubmissionNotification($bookSubmission));

            // Send email notification to author
            bookSubmitEmailNotify($bookSubmission,$bookSubmission->client);
            bookSubmittedNotifyForAuthor($bookSubmission,$bookSubmission->client);

            return response()->json([
                'message' => 'Book submitted successfully! Our team will review it shortly.',
                'submission_id' => $bookSubmission->id
            ], 200);

        } catch (\Exception $e) {
            // Clean up uploaded files if there was an error
            if ($bookFileUploaded) {
                $file->delete($bookFileUploaded);
            }
            if ($coverImageUploaded) {
                $file->delete($coverImageUploaded);
            }

            return response()->json([
                'message' => 'An error occurred while processing your submission. Please try again.'
            ], 500);
        }
    }

    public function show($id)
    {
        $data['pageTitle'] = __('Submit Your Book');
        $data['bookSubmission'] = BookSubmission::findOrFail($id);
        
        return view('user.book_submission.show', $data);
    }
}
