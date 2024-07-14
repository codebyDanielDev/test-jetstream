<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

class PhoneNumber implements ValidationRule
{
    protected $country;

    public function __construct($country)
    {
        $this->country = $country;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            // Concatenate country code with phone number if necessary
            $fullNumber = $this->country . $value;
            $phoneProto = $phoneUtil->parse($fullNumber, null);
            if (!$phoneUtil->isValidNumber($phoneProto)) {
                $fail('The :attribute is not a valid phone number for the selected country.');
            }
        } catch (NumberParseException $e) {
            $fail('The :attribute is not a valid phone number for the selected country.');
        }
    }
}
