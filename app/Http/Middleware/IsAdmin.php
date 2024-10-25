<?php

namespace Sibas\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class IsAdmin
{
    /**
     * @var Guard
     */
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && $this->auth->user()->type->code === 'ADT' || $this->auth->user()->type->code === 'OPT') {
            return redirect()->route('adm::');
        }

        return $next($request);
    }
}
