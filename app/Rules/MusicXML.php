<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MusicXML implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($value);

        if ($xml === false) {
            $fail('The :attribute must be a valid MusicXML file');
        } else {
            $errors = libxml_get_errors();
             // TODO: add valid MusicXML validation
            if (sizeof($errors) > 0) {
                $fail('The :attribute must be a valid MusicXML file');
            }
            libxml_clear_errors();
        }
    }
}
