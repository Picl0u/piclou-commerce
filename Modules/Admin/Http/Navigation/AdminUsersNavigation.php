<?php

namespace Modules\Admin\Http\Navigation;

use App\Http\Picl0u\AdminNavigationInterface;

class AdminUsersNavigation implements AdminNavigationInterface
{
    public function render()
    {
        return view('admin::navigation.users');
    }

}
