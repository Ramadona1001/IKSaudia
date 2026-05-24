<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = (string) $value;
        $min = (int) config('security.password.min_length', 12);

        if (strlen($password) < $min) {
            $fail(__('validation.min.string', ['attribute' => $attribute, 'min' => $min]));

            return;
        }

        $checks = [
            '/[a-z]/' => 'lowercase letter',
            '/[A-Z]/' => 'uppercase letter',
            '/[0-9]/' => 'number',
            '/[^a-zA-Z0-9]/' => 'special character',
        ];

        foreach ($checks as $pattern => $label) {
            if (! preg_match($pattern, $password)) {
                $fail("The :attribute must contain at least one {$label}.");

                return;
            }
        }
    }
}
