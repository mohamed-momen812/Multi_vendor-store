<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Filter implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $forbiddenValue; // data will pass in construct

    public function __construct($forbiddenValue)
    {
        $this->forbiddenValue = $forbiddenValue;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(in_array(strtolower($value), $this->forbiddenValue)) {
            $fail("This value is forbidden");
        }
    }
}
