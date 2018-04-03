<?php

namespace Modules\Website\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Content\Entities\Content;
use Modules\Newsletter\Entities\NewsletterContents;
use Modules\Order\Entities\OrdersProducts;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ShopCategory;
use Modules\Slider\Entities\Slider;

class WebsiteController extends Controller
{
    protected $viewPath = 'website::';

    /**
     * Homepage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homepage()
    {
        // Slider - TODO CHANGER NOM POSITION
        $sliders = Slider::where("published",1)->orderBy('order','ASC')->get();

        // Catégories
        $categories = ShopCategory::where('published',1)
            ->where('on_homepage',1)
            ->orderBy('order','ASC')
            ->get();

        // Meilleures ventes
        $bestSale = OrdersProducts::selectRaw('*, sum(product_id) as sum')
            ->groupBy('product_id')
            ->orderByRaw('SUM(product_id) DESC')
            ->limit(9)
            ->get();

        // Ventes Flash
        $flashSales = Product::flashSales();

        // Sélection de la semaine
        $weekSelections = Product::select('id','name','slug','summary','price_ttc','reduce_price','reduce_percent','image', 'updated_at')
            ->where('published',1)
            ->where('week_selection',1)
            ->orderBy('order','ASC')
            ->get();

        // Contenus page d'accueil
        $contents = Content::select('id','image','name','slug','summary','description','content_category_id')
            ->where('published', 1)
            ->where('on_homepage', 1)
            ->orderBy('order','ASC')
            ->get();

        // Contenu newsletter
        $newsletter = NewsletterContents::first();

        return view($this->viewPath . 'index',
            compact(
                'sliders',
                'categories',
                'bestSale',
                'flashSales',
                'weekSelections',
                'contents',
                'newsletter'
            )
        );
    }

    /**
     * Multilangue
     * @param $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLocale($locale)
    {
        if(in_array($locale, config('ikCommerce.languages'))){
            session(['locale' => $locale]);
        }
        return redirect()->back();
    }

    /**
     * Erreur 404
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notFound()
    {
        $contents = Content::select('id','image','name','slug','summary','content_category_id','updated_at')
        ->where('published', 1)
        ->where('on_homepage', 1)
        ->orderBy('order','ASC')
        ->get();

        $arianne = [
          __('generals.home') => '/',
            __('website::front.404_title') => route('error.404')
        ];
        return view($this->viewPath . 'errors.404', compact('arianne', 'contents'));
    }
    /**
     * Erreur 404
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fatalError()
    {
        $contents = Content::select('id','image','name','slug','summary','content_category_id','updated_at')
            ->where('published', 1)
            ->where('on_homepage', 1)
            ->orderBy('order','ASC')
            ->get();

        $arianne = [
            __('generals.home') => '/',
            __('website::front.500_title') => route('error.500')
        ];
        return view($this->viewPath . 'errors.500', compact('arianne', 'contents'));
    }
}
