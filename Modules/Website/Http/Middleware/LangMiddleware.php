<?php

namespace Modules\Website\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_null(session()->get('locale'))) {
            session(['locale' => config('app.locale')]);
        }
        app()->setLocale(session()->get('locale'));
        return $next($request);
    }
}
