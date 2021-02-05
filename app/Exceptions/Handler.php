<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Auth\Access\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
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

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Resource not found: ModelNotFoundException'
                ], 404);
            }

//            if ($exception instanceof NotFoundHttpException) {
//                return response()->json([
//                    'success' => false,
//                    'error' => 'Resource not found: NotFoundHttpException'
//                ], 404);
//            }

//            if ($exception instanceof AuthorizationException) {
//                return response()->json([
//                    'success' => false,
//                    'error' => 'Action not allowed: AuthorizationException'
//                ], 403);
//            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'error' => 'These credentials do not match our records'
                ], 401);
            }

            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthenticated'
                ], 401);
            }

            return parent::render($request, $exception);
        }
    }
}
