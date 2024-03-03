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
        $moodNames = array_keys($value);
        $existingMoods = Mood::whereIn('name', $moodNames)->get();

        foreach ($moodNames as $moodName) {
            if (!$existingMoods->contains('name', $moodName)) {
                $fail("The mood {$moodName} does not exist.");
            }
        }
    }
}
