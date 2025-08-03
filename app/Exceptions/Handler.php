<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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


    private function logToSentryWithTimeout(Throwable $e): void
    {
        dispatch(function () use ($e) {
            try {
                Log::channel('sentry')->error('Exception occurred', [
                    'exception' => $e,
                ]);
            } catch (\Exception $sentryException) {
                Log::error('Sentry logging failed', [
                    'original_error' => $e->getMessage(),
                    'sentry_error' => $sentryException->getMessage(),
                ]);
            }
        });
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof OAuthServerException) {
            return redirect()->route('login.view')->withErrors([
                'email' => 'Oops! Something went wrong. This could be due to invalid client. Please start over'
            ]);
        }

        if ($e instanceof AuthenticationException) {
            return parent::render($request, $e);
        }

        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        if ($e instanceof TokenMismatchException) {
            return parent::render($request, $e);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed',
                'errors' => null,
            ], 405);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
                'errors' => null,
            ], 404);
        }

        if ($e instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden action',
                'errors' => null,
            ], 403);
        }

        if ($e instanceof \Exception) {
            $this->logToSentryWithTimeout($e);

            $code = $e->getCode();
            $httpCode = ($code >= 100 && $code <= 599) ? $code : 500;

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'errors' => null,
            ], $httpCode);
        }

        return parent::render($request, $e);
    }
}
