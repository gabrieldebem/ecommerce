<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->renderable(function (AuthenticationException $e, $request) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Access Denied',
                ], 403);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Not Found',
                ], 404);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Method Not Allowed',
                ], 405);
            }
        });

        $this->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'message' => 'The given data was invalid',
                'errors' => $e->errors(),
            ], 422);
        });

        $this->renderable(function (Exception $e, $request) {
            return response()
                ->json(
                    [
                        'message' => $e->getMessage(),
                        'errors' => env('APP_DEBUG') ? $e->getTrace() : [],
                    ],
                    $e->getCode(),
                );
        });

        $this->renderable(function (Throwable $e) {
            return response()
                ->json(
                    [
                        'message' => $e->getMessage(),
                        'errors' => env('APP_DEBUG') ? $e->getTrace() : [],
                    ],
                    500,
                );
        });
    }
}
