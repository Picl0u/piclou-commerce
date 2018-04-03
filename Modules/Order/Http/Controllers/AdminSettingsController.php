<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use anlutro\LaravelSettings\Facade as Setting;
use Modules\Content\Entities\Content;
use Modules\Order\Entities\Countries;

class AdminSettingsController extends Controller
{
    protected $viewPath = 'order::admin.';

    protected $route = 'settings.orders';

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

    public function orders()
    {
        $data = [
            'noAccount' => Setting::get('orders.noAccount'),
            'orderAgain' => Setting::get('orders.orderAgain'),
            'minAmmout' => Setting::get('orders.minAmmout'),
            'stockBooked' => Setting::get('orders.stockBooked'),
            'countryId' => Setting::get('orders.countryId'),
            'freeShippingPrice' => Setting::get('orders.freeShippingPrice'),
            'productQuantityAlert' => Setting::get('orders.productQuantityAlert'),
            'cgv' => Setting::get('orders.cgv'),
            'cgvId' => Setting::get('orders.cgvId'),
            'acceptId' => Setting::get('orders.acceptId'),
            'refuseId' => Setting::get('orders.refuseId'),
        ];

        /* Pays */
        $countries = Countries::select('id','name')->where('activated',1)->get();
        $countriesArray = [];
        foreach($countries as $country) {
            $countriesArray[$country->id] = $country->name;
        }

        /* Pays */
        $contents = Content::select('id','name')->where('published',1)->get();
        $contentsArray = [];
        foreach($contents as $content) {
            $contentsArray[$content->id] = $content->name;
        }


        return view($this->viewPath . 'settings',compact('data','countriesArray', 'contentsArray'));
    }

    public function ordersStore(Request $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route);
        }

        $noAccount = 0;
        if ($request->noAccount == "on") {
            $noAccount = 1;
        }
        $orderAgain = 0;
        if ($request->orderAgain == "on") {
            $orderAgain = 1;
        }
        $cgv = 0;
        if ($request->cgv == "on") {
            $cgv = 1;
        }

        Setting::set('orders.noAccount', $noAccount);
        Setting::set('orders.orderAgain', $orderAgain);
        Setting::set('orders.cgv ', $cgv);
        Setting::set('orders.minAmmout', $request->minAmmout);
        Setting::set('orders.stockBooked', $request->stockBooked);
        Setting::set('orders.countryId', $request->countryId);
        Setting::set('orders.freeShippingPrice', $request->freeShippingPrice);
        Setting::set('orders.productQuantityAlert', $request->productQuantityAlert);
        Setting::set('orders.cgvId', $request->cgvId);
        Setting::set('orders.acceptId', $request->acceptId);
        Setting::set('orders.refuseId', $request->refuseId);

        Setting::save();

        session()->flash('success','Les paramètres ont bien été mis à jours');
        return redirect()->route($this->route);
    }

}
