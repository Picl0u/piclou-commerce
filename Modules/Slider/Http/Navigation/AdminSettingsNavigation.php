<?php
namespace Modules\Slider\Http\Navigation;

use App\Http\Picl0u\AdminNavigationInterface;

class AdminSettingsNavigation implements AdminNavigationInterface
{
    public function render()
    {
       return view('slider::admin.navigation.settings');
    }

}