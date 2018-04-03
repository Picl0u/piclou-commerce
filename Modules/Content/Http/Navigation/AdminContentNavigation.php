<?php

namespace Modules\Content\Http\Navigation;
use App\Http\Picl0u\AdminNavigationInterface;

class AdminContentNavigation implements AdminNavigationInterface
{
    public function render()
    {
        return view('content::admin.navigation');
    }

}