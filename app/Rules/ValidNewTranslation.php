<?php

namespace App\Rules;

use App\Translation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidNewTranslation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('The :attribute is required!');
        }

        $locales = Translation::all()->map(fn (Translation $translation) => $translation->locale)->toArray();
        if (in_array($value, $locales)) {
            $fail('The :attribute is already exist!');
        }
    }
}
