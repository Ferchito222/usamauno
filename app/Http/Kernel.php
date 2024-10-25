<?php

namespace Sibas\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Sibas\Http\Middleware\ApiMiddleware;
use Sibas\Http\Middleware\Cashier;
use Sibas\Http\Middleware\JwtMiddleware;
use Sibas\Http\Middleware\LoggedIn;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Styde\Html\Alert\Middleware::class,
        \Sibas\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Sibas\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => \Sibas\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'      => \Sibas\Http\Middleware\RedirectIfAuthenticated::class,

        'is_admin'   => \Sibas\Http\Middleware\IsAdmin::class,
        'is_user'    => \Sibas\Http\Middleware\IsUser::class,
        'cashier'    => Cashier::class,
        'logged_in'  => LoggedIn::class,
        'api_jwt'    => ApiMiddleware::class,
        'auth.jwt'   => JwtMiddleware::class,
    ];
}
