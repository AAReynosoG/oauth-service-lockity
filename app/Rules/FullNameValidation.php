<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FullNameValidation implements Rule
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
        return preg_match('/^[A-Za-záéíóúüñÁÉÍÓÚÜÑ ]{3,100}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be 3–100 characters and contain only letters and spaces. Accents are allowed, but numbers and symbols are not.';
    }
}
