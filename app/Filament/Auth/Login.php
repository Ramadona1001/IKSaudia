<?php

namespace App\Filament\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $email = $this->form->getState()['email'] ?? null;

        if ($email) {
            $user = User::query()->where('email', $email)->first();

            if ($user instanceof User) {
                if (! $user->is_active) {
                    throw ValidationException::withMessages([
                        'data.email' => 'This account has been deactivated.',
                    ]);
                }

                if ($user->locked_until && $user->locked_until->isFuture()) {
                    throw ValidationException::withMessages([
                        'data.email' => 'Too many failed attempts. Try again later.',
                    ]);
                }
            }
        }

        try {
            return parent::authenticate();
        } catch (TooManyRequestsException $exception) {
            throw $exception;
        }
    }
}
