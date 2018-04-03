<?php
namespace Modules\User\Http\Widget;

use App\Http\Picl0u\AdminWidgetInterface;
use Modules\User\Entities\User;

class UserWidget implements AdminWidgetInterface
{
    public function render()
    {
        $users = User::where('role', 'user')->orderBy('id','DESC')->limit(10)->get();
        return view("user::admin.widget", compact('users'));
    }

}