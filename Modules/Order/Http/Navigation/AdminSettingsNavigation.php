<?php
namespace Modules\Order\Http\Navigation;

use App\Http\Picl0u\AdminNavigationInterface;

class AdminSettingsNavigation implements AdminNavigationInterface{
    public function render()
    {
        return view('order::admin.navigation.settings');
    }


}