<?php
namespace Modules\Order\Http\Navigation;

use App\Http\Picl0u\AdminNavigationInterface;

class AdminOrderNavigation implements AdminNavigationInterface
{
    public function render()
    {
        return view('order::admin.navigation.order');
    }
}