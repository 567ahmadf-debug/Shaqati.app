<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        ResetPassword::createUrlUsing(
            function (User $user, string $token): string {
                $baseUrl = rtrim(config('app.frontend_url'), '/');

                return $baseUrl . '/reset-password?' . http_build_query([
                    'token' => $token,
                    'email' => $user->getEmailForPasswordReset(),
                ]);
            }
        );

        RateLimiter::for('login', function (Request $request) {
            $email = Str::lower(
                trim((string) $request->input('email', ''))
            );

            return [
                Limit::perMinute(5)
                    ->by($request->ip() . '|' . $email),

                Limit::perMinute(20)
                    ->by('login-ip:' . $request->ip()),
            ];
        });
    }
}
