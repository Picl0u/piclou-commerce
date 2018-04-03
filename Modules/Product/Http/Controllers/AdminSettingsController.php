<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use anlutro\LaravelSettings\Facade as Setting;

class AdminSettingsController extends Controller
{
    protected $viewPath = 'product::admin.';

    protected $route = 'settings.products';

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
    public function products()
    {
        $data = [
            'paginate' => Setting::get('products.paginate'),
            'orderField' => Setting::get('products.orderField'),
            'orderDirection' => Setting::get('products.orderDirection'),
            'socialEnable' => Setting::get('products.socialEnable'),
            'commentEnable' => Setting::get('products.commentEnable'),
            'zoomEnable' => Setting::get('products.zoomEnable'),
            'modalEnable' => Setting::get('products.modalEnable'),
        ];

        return view($this->viewPath . 'settings',compact('data'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProducts(Request $request)
    {

        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route);
        }

        $socialEnable = 0;
        if($request->socialEnable == 'on') {
            $socialEnable = 1;
        }
        $commentEnable = 0;
        if($request->commentEnable == 'on') {
            $commentEnable = 1;
        }
        $zoomEnable = 0;
        if($request->zoomEnable == 'on') {
            $zoomEnable = 1;
        }
        $modalEnable = 0;
        if($request->modalEnable == 'on') {
            $modalEnable = 1;
        }

        Setting::set('products.paginate', $request->paginate);
        Setting::set('products.orderField', $request->orderField);
        Setting::set('products.orderDirection', $request->orderDirection);
        Setting::set('products.socialEnable', $socialEnable);
        Setting::set('products.commentEnable', $commentEnable);
        Setting::set('products.zoomEnable', $zoomEnable);
        Setting::set('products.modalEnable', $modalEnable);

        Setting::save();

        session()->flash('success','Les paramètres ont bien été mis à jours');
        return redirect()->route($this->route);
    }
}
