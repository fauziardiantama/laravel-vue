<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = '/^(\+|)[0-9]{1,2}[\d\s-]{5,17}[\d]$/';

        if (!preg_match($pattern, $value))
        {
            $fail('The :attribute must be a valid phone number.');
        }
    }
}
