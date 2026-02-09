<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

     // ✅ Add this method
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException && $exception->getStatusCode() === 403) {
            // Check if user is authenticated
            if (Auth::check()) {
                $user = Auth::user();
                // Check user role
                if ($user->role === USER_ROLE_ADMIN) {
                    return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
                } else {
                    return redirect()->route('user.dashboard')->with('error', 'Access denied.');
                }
            } else {
                // If not authenticated, redirect to login or fallback
                return redirect()->route('login')->with('error', 'Please login to continue.');
            }
        }

        return parent::render($request, $exception);
    }
}
