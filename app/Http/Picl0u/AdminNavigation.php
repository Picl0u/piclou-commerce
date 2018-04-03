<?php
namespace App\Http\Picl0u;

use Illuminate\Support\Facades\Auth;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Json;

class AdminNavigation{

    private $navigationItems = [];

    public function __construct()
    {
        $this->getModules();
    }

    /**
     * Retourne le menu en HTML
     * @return string
     */
    public static function render(): string
    {
        $instance = (new self());
        $html = '';
        foreach ($instance->navigationItems as $key => $items) {
            $html .= '<ul><li class="cd-label">' . __("admin::navigation." . $key) . '</li>';
                $html .= array_reduce($items, function ($html, AdminNavigationInterface $widget) {
                    return $html . $widget->render();
                });
            $html .= '</ul>';
        }
        return $html;
    }

    private function getModules()
    {

        $adminMenu = [];

        foreach(Module::getOrdered() as $module) {

            $user = Auth::user();
            if (
                $user->hasRole(config('ikCommerce.superAdminRole')) ||
                $user->can('access - '.$module->getAlias())
            ) {
                $path = $module->getPath();
                if (!empty($this->isActive($path)) && file_exists($path . "/Config/config.php")) {
                    $config = require($path . "/Config/config.php");

                    if (isset($config['admin.navigation']) && !empty($config['admin.navigation'])) {
                        foreach ($config['admin.navigation'] as $key => $items) {
                            foreach ($items as $item) {
                                $adminMenu[$key][] = new $item();
                            }
                        }
                    }
                }
            }
        }

        if (!empty(config('ikCommerce.adminMenuOrders')) && is_array(config('ikCommerce.adminMenuOrders'))) {
            foreach (config('ikCommerce.adminMenuOrders') as $menu) {
                foreach ($adminMenu as $key => $items) {
                    if($key == $menu) {
                        $this->navigationItems[$key] = $items;
                        unset($adminMenu[$key]);
                    }
                }
            }
            foreach ($adminMenu as $key => $items) {
                $this->navigationItems[$key] = $items;
            }
        } else{
            $this->navigationItems = $adminMenu;
        }

    }

    /**
     * Retourne si un module est activé ou non
     * @param string $path
     * @param null $file
     * @return mixed
     */
    private function isActive(string $path, $file = null)
    {
        if(is_null($file)){
            $file = 'module.json';
        }
        $json = new Json($path . '/' . $file);
        return $json->get('active');
    }

}
