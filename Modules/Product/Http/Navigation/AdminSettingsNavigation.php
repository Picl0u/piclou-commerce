<?php
namespace Modules\Product\Http\Navigation;

use App\Http\Picl0u\AdminNavigationInterface;

class AdminSettingsNavigation implements AdminNavigationInterface
{
    public function render()
    {
        return view('product::admin.navigation.setting');
    }

}
