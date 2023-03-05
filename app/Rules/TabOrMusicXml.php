<?php

namespace App\Rules;

use App\Libraries\MusicXmlValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TabOrMusicXml implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // first check if start of file seems like an xml file
        // if it looks like it is then attempt to validate xml format and then musicxml spec
        if (substr(trim($value), 0, 5) === '<?xml') {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($value);

            if ($xml === false) {
                $fail('The :attribute must be a valid XML file.');
            } else {
                $musicXmlValidator = new MusicXmlValidator();
                $validMusicXml = $musicXmlValidator->isValidMusicXmlString($value);

                if (! $validMusicXml) {
                    $fail('The :attribute XML file must follow the correct MusicXML spec.');
                }
                libxml_clear_errors();
            }
        }

        // if it's not similar to an xml file then assume it's a text tab
    }
}
