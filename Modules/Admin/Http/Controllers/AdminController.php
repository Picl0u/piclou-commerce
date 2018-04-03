<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Picl0u\AdminWidgetInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Nwidart\Modules\Facades\Module;

class AdminController extends Controller
{
    protected $viewPath = 'admin::';

    public function login()
    {
        if(Auth::user()){
            if(Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        return view($this->viewPath . 'login');
    }


    public function logout(Request $request)
    {
        $this->guard()->logout();
        session()->flash('success',"Vous êtes déconnecté");
        $request->session()->invalidate();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $widgetList = $this->getWidgets();
        $widgets = array_reduce($widgetList, function ($html, AdminWidgetInterface $widget) {
           return $html . $widget->render();
        });

        return view($this->viewPath . 'dashboard', compact('widgets'));
    }

    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Récupère la liste des widgets
     * @return array
     */
    private function getWidgets()
    {
        $user = Auth::user();
        $widgets = [];
        foreach (Module::enabled() as $module) {
            if ($user->hasRole(config('ikCommerce.superAdminRole')) || $user->can('access - '.$module->getAlias())) {

                if (file_exists($module->getPath()."/Config/config.php")) {
                    $config = require($module->getPath()."/Config/config.php");
                    if(isset($config['admin.widget']) && !empty($config['admin.widget'])) {
                        if (is_array($config['admin.widget'])) {
                            foreach($config['admin.widget'] as $widget) {
                                $widgets[] = new $widget();
                            }
                        } else {
                            $widgets[] = new $config['admin.widget']();
                        }
                    }
                }

            }
        }
        return $widgets;
    }

}
