<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{

    protected function isPassportAuthorizeRequest(Request $request): bool
    {
        return $request->is('oauth/authorize') &&
            $request->isMethod('GET') &&
            $request->filled([
                'response_type',
                'client_id',
                'redirect_uri',
                'code_challenge',
                'code_challenge_method',
                'state',
                'device'
            ]);
    }


    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($this->isPassportAuthorizeRequest($request)) {
            session([
                'oauth_params' => $request->only([
                    'client_id',
                    'redirect_uri',
                    'code_challenge',
                    'code_challenge_method',
                    'response_type',
                    'state',
                    'device'
                ])
            ]);
        }

        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
