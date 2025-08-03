<?php

namespace App\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\Rule;
use PhpParser\Node\Stmt\TryCatch;

class TurnstileValidation implements Rule
{

    protected $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        try {
            $response = Http::asForm()->post("https://challenges.cloudflare.com/turnstile/v0/siteverify", [
                'secret' => env('TURNSTILE_SECRET_KEY'),
                'response' => $value,
                'remoteip' => $this->request->ip(),
            ]);

            $data = $response->json();

            return isset($data['success']) && $data['success'] === true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Turnstile verification failed. Please try again.';
    }
}
