<?php
namespace Modules\Newsletter\Http\Navigation;

use App\Http\Picl0u\AdminNavigationInterface;

class AdminNewsletterNavigation implements AdminNavigationInterface
{
    public function render()
    {
        return view("newsletter::admin.navigation");
    }

}