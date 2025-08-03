<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
}
