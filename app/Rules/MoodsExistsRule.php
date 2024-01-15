<?php

namespace App\Rules;

use App\Models\Mood;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MoodsExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach (array_keys($value) as $moodName) {

            $mood = Mood::where('name', $moodName)->first();

            if (! $mood) {
                $fail("The mood {$moodName} does not exist.");
            }
        }
    }
}
