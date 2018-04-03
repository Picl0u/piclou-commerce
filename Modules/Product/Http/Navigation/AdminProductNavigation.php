<?php
namespace Modules\Product\Http\Navigation;

use App\Http\Picl0u\AdminNavigationInterface;

class AdminProductNavigation implements AdminNavigationInterface
{
    public function render()
    {
        return view('product::admin.navigation.product');
    }
}