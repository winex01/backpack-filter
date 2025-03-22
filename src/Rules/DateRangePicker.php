<?php

namespace Winex01\BackpackFilter\Rules;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class DateRangePicker implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $attributeName = $this->strToHumanReadable($attribute);

        if (!Str::contains($value, '-')) {
            $fail(__("Invalid date range format for {$attributeName}."));
            return;
        }

        $dates = explode('-', $value);

        // Ensure the date range contains exactly two dates
        if (count($dates) !== 2) {
            $fail(__("Invalid date range format for {$attributeName}."));
            return; // Exit the function early
        }

        // Trim whitespace from each date
        $startDate = trim($dates[0]);
        $endDate = trim($dates[1]);

        // Parse dates with Carbon
        try {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        } catch (\Exception $e) {
            $fail(__("Invalid date format for {$attributeName}."));
            return; // Exit the function early
        }

        // Ensure the start date is less than the end date
        if ($startDate->gt($endDate)) {
            $fail(__("The end date must be greater than or equal to the start date for {$attributeName}."));
        }
    }

    public function strToHumanReadable($string, $capitalizeAllWords = false)
    {
        $snakeCase = Str::replace('_', ' ', Str::snake($string)); // Convert camelCase to snake_case and replace underscores

        return $capitalizeAllWords ? ucwords($snakeCase) : ucfirst($snakeCase); // Use ucwords() or ucfirst() based on the second parameter
    }
}
