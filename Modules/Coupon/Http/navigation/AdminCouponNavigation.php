<?php
namespace Modules\Coupon\Http\Navigation;

use App\Http\Picl0u\AdminNavigationInterface;

class AdminCouponNavigation implements AdminNavigationInterface
{
    public function render()
    {
        return view('coupon::admin.navigation');
    }

}