<?php

namespace Modules\Slider\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use anlutro\LaravelSettings\Facade as Setting;

class AdminSettingsController extends Controller
{
    protected $viewPath = 'slider::admin.';

    protected $route = 'settings.slider';

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
    public function slider()
    {
        $data = [
            'arrows' => Setting::get('slider.arrows'),
            'dots' => Setting::get('slider.dots'),
            'type' => Setting::get('slider.type'),
            'transition' => Setting::get('slider.transition'),
            'slideDuration' => Setting::get('slider.slideDuration'),
            'transitionDuration' => Setting::get('slider.transitionDuration'),
        ];

        return view($this->viewPath . 'settings',compact('data'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSlider(Request $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route);
        }

        $arrows = 0;
        if ($request->arrows == "on") {
            $arrows = 1;
        }
        $dots = 0;
        if ($request->dots == "on") {
            $dots = 1;
        }

        Setting::set('slider.arrows', $arrows);
        Setting::set('slider.dots', $dots);
        Setting::set('slider.type', $request->type);
        Setting::set('slider.transition', $request->transition);
        Setting::set('slider.slideDuration', $request->slideDuration);
        Setting::set('slider.transitionDuration', $request->transitionDuration);

        Setting::save();

        session()->flash('success','Les paramètres ont bien été mis à jours');
        return redirect()->route($this->route);

    }
}
