<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{

    /**
     * @var string
     */
    protected $viewPath = 'admin::permissions.';

    /**
     * @var string
     */
    protected $route = 'admin.permissions.';

    /**
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    /**
     * @param string $viewPath
     */
    public function setViewPath(string $viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route)
    {
        $this->route = $route;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $roles = Role::where('guard_name', '!=', 'super_admin')->get();
        $modules = Module::getByStatus(1);

        return view($this->viewPath . 'index', compact('roles','modules'));

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function savePermission(Request $request)
    {

        if(config('ikCommerce.demo')) {
            return response("Cette fonctionnalité a été désactivée pour la version démo.", 200)
                ->header('Content-Type', 'text/plain');
        }

        $role = Role::where('guard_name', $request->role)->first();
        if(empty($role)){
            return response("Le rôle est introuvable", 404)
                ->header('Content-Type', 'text/plain');
        }
        $permission = Permission::where('name', $request->permission.' - '.$request->module)
            ->where('guard_name', $role->guard_name)
            ->first();

        if(empty($permission)){
            $permission = Permission::create([
                'name' => $request->permission.' - '.$request->module,
                'guard_name' => $role->guard_name
            ]);
        }

        if(!empty($request->checked)){
            $permission->assignRole($role);
        } else {
            $permission->removeRole($role);
        }

        return response("La permission a bien été mise à jours", 200)
            ->header('Content-Type', 'text/plain');

    }
}
