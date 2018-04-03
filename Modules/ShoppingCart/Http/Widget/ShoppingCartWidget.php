<?php

namespace Modules\ShoppingCart\Http\Widget;


use App\Http\Picl0u\AdminWidgetInterface;
use Modules\ShoppingCart\Entities\Shoppingcart;

class ShoppingCartWidget implements AdminWidgetInterface
{
    public function render()
    {
        $totalWhishList = Shoppingcart::where('instance','whishlist')->count();
        $totalCart = Shoppingcart::where('instance','shopping')->count();

        return view('shoppingcart::admin.widget', compact('totalCart','totalWhishList'));
    }


}