<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminPrivilege
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        $route =  $request->route()->getName();

        if (!$user) {
            return abort(404);
        }

        $privileges = $user->privileges;

        $accessibles = collect([
            str_contains($route, 'page.getIndex'),
            str_contains($route, 'Json'),
            str_contains($route, 'store'),
            str_contains($route, 'update'),
            str_contains($route, 'post'),
            str_contains($route, 'put'),
        ]);

        $hasAccess = $accessibles->contains(true);

        $isPermitted = !!$privileges->first(function($key, $privilege) use ($route) {
            $isValidRoute = $privilege->menuAdmin->link === $route;
            $isAccessible = !!$privilege->can_access;

            return $isValidRoute && $isAccessible;
        });

        if (!$hasAccess && !$isPermitted) {
            return response()->view('admin::forbidden', [], 404);
        }

        return $next($request);
    }
}
