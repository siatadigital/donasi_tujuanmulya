<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Admin
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
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
    	if (! $this->auth->user()) {
    		return abort(404);
    	}

        if ( ! $this->auth->user()->is_superadmin) {
            if ($request->ajax()) {
                return response('Forbidden.', 404);
            } else {
                return abort(404, 'Actually you dont have permissions to this');
            }
        }

        return $next($request);
    }
}
