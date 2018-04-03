<?php

namespace Modules\ShoppingCart\Http\Middleware;

use Closure;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderAccessMiddleware
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
        if (!empty(session()->get('custommers')) && !empty(Cart::instance('shopping')->count())) {
            return $next($request);
        }
        session()->flash('error',__('user.permission_error'));
        return redirect()->route('cart.show');
    }
}
