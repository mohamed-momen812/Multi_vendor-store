<?php

namespace App\Providers;

use App\Actions\Fortify\AuthenticateUser;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Exceptions\Renderer\Frame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // must be here not in boot method because it must be before booting the service to change config in fortify
        $request = request();

        if($request->is('admin/*')){
            Config::set('fortify.guard', 'admin');
            Config::set('fortify.passwords', 'admins');
            Config::set('fortify.prefix', 'admin'); // access all routes with prefix admin
            Config::set('fortify.home', 'admin/dashboard'); // access all routes with prefix admin
        }
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

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });




        // here you define all views using prefix, using prefix and define views instead of define all views one by one
        if(Config::get('fortify.guard') === 'admin') {
            Fortify::viewPrefix('auth.');

            // this is how to modify fortify fifault behavior by calling the method and pass costom fuction
            // only admin login user this custom authenticated function, if users laravel will use the default authenticateUseing
            // Class/method references like Fortify::authenticateUsing(): Laravel doesn’t instantiate the class automatically. If you reference a class and method like [AuthenticateUser::class, 'authenticate'], the method must be static, or you must manually instantiate the class.
            Fortify::authenticateUsing([new AuthenticateUser, 'authenticate']);
        } else {
            Fortify::viewPrefix('front.auth.');
        }


        // access to routes from laravel/fortify/routes/routes.php
        // // till laravel what is the required view
        // Fortify::loginView('auth.login');
        // // Fortify::twoFactorChallengeView('two-factor-challenge');
        // Fortify::registerView("auth.register");
        // Fortify::requestPasswordResetLinkView('auth.forgot-password');
        // Fortify::resetPasswordView('auth.reset-password');
        // Fortify::verifyEmailView('auth.verify-email');
        // // Fortify::confirmPasswordView('confirm-password');
    }
}
