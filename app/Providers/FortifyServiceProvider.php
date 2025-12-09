<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            // Store service ID in session if present in query string
            if (request()->query('service_id')) {
                session(['service_id_after_register' => request()->query('service_id')]);
            }
            return view('auth.register');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });
        
        // Custom redirect after registration
        Fortify::redirects('register', function (Request $request) {
            // Store the service_id if present for post-verification redirect
            if (session('service_id_after_register')) {
                // Keep it in session for after verification
            }
            // Always redirect to email verification after registration
            return route('verification.notice');
        });
        
        // Custom redirect after email verification
        Fortify::redirects('email-verification', function (Request $request) {
            $user = $request->user();
            
            // Check session for service ID (stored during registration)
            if (session('service_id_after_register')) {
                $serviceId = session('service_id_after_register');
                session()->forget('service_id_after_register');
                return route('client.booking.show', $serviceId);
            }
            
            // Redirect based on user type
            if ($user && $user->user_type === 'provider') {
                return '/admin/dashboard';
            }
            return '/'; // Home page for clients
        });
        
        // Custom redirect after login
        Fortify::redirects('login', function (Request $request) {
            $user = $request->user();
            
            // Check if there's an intended redirect URL
            if ($intended = session('url.intended')) {
                session()->forget('url.intended');
                return $intended;
            }
            
            if ($user && $user->user_type === 'provider') {
                return '/admin/dashboard';
            }
            return '/'; // Home page for clients
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
