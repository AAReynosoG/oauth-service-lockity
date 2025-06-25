<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return preg_match('/^(?=.{5,100}$)[\p{L}0-9._-]+@[\p{L}0-9-]+(?:\.[\p{L}0-9-]+)*\.[\p{L}0-9]{2,}$/', $value);

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Email must be 5–100 characters, no spaces/accents, and follow a valid format (e.g., name@example.com).';
    }
}
