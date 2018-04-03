<?php
namespace Modules\User\Http\Navigation;

use App\Http\Picl0u\AdminNavigationInterface;

class AdminUserNavigation implements AdminNavigationInterface
{
    public function render()
    {
        return view('user::admin.navigation');
    }

}