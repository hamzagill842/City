<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class AgeLessThanTen implements Rule
{
    public function passes($attribute, $value)
    {
        // Parse the date of birth and calculate the age
        $dateOfBirth = Carbon::createFromFormat('Y-m-d', $value);
        $age = $dateOfBirth->diffInYears(now());

        // Check if the age is less than 15
        return $age > 15;
    }

    public function message()
    {
        return 'The date of birth must be greater than 15 years old.';
    }
}
