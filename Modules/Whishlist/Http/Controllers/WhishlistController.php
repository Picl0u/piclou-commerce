<?php

namespace Modules\Whishlist\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Entities\Product;
use Gloudemans\Shoppingcart\Facades\Cart;

class WhishlistController extends Controller
{
    protected $viewPath = 'whishlist::';

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $arianne = [
            __('generals.home') => '/',
            __('user::user.my_whishlist') => route('whishlist.index'),
        ];
        return view($this->viewPath . 'index', compact('arianne'));
    }

    /**
     * Permet d'ajouter un produit dans la panier
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct(Request $request)
    {
        if(auth()->check()) {
            if(auth()->user()->role == 'user') {

                $product = Product::where('uuid', $request->uuid)->first();
                if (empty($product)) {
                    return response('Le produit demandé est introuvable', 404)
                        ->header('Content-Type', 'text/plain');
                }

                if ($product->stock_available < $request->quantity) {
                    return response("Il n'y a pas assez de produits en stock.", 404)
                        ->header('Content-Type', 'text/plain');
                }

                $vat = $product->Vat;
                $percent = 1+($vat->percent/100);

                $price = $product->price_ttc;
                if(
                    (!empty($product->reduce_price) && !is_null($product->reduce_price)) ||
                    (!empty($product->reduce_percent) && !is_null($product->reduce_percent))
                ) {
                    if(!empty($product->reduce_price) && !is_null($product->reduce_price)){
                        $price = $product->price_ttc - $product->reduce_price;
                    }else{
                        $price = $product->price_ttc - ($product->price_ttc * (($product->reduce_percent/100)));
                    }
                }
                $addCart = [
                    'id' => $product->reference,
                    'name' => $product->getTranslation('name', config('app.locale')),
                    'price' => $price/$percent,
                    'qty' => 1,
                    'options' => [
                        'image' => $product->getMedias('image','src')
                    ]
                ];

                Cart::instance('whishlist')->restore(Auth::user()->id);
                Cart::instance('whishlist')->add($addCart)->associate('Product');
                $cart = [
                    'count' => Cart::instance('whishlist')->count(),
                    'message' => "Il y'a " . Cart::instance('whishlist')->count() . " articles dans votre liste de souhait",
                    'product' => $addCart
                ];
                Cart::instance('whishlist')->store(Auth::user()->id);
                return response()->json($cart);
            }
        }

        return response("Vous devez être connecté pour ajouter un produit à votre liste de souhait", 403)
            ->header('Content-Type', 'text/plain');
    }


    /**
     * @param string $rowId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addCart(string $rowId)
    {
        $row = Cart::instance('whishlist')->get($rowId);

        Cart::instance('whishlist')->restore(Auth::user()->id);
        Cart::instance('shopping')->restore(Auth::user()->uuid);

        Cart::instance('whishlist')->remove($rowId);
        $addCart = [
            'id' => $row->id,
            'name' => $row->name,
            'price' => $row->price,
            'qty' => $row->qty,
            'options' => [
                'image' => $row->options->image
            ]
        ];
        Cart::instance('shopping')->add($addCart);

        Cart::instance('whishlist')->store(Auth::user()->id);
        Cart::instance('shopping')->store(Auth::user()->uuid);

        session()->flash('success',"Votre produit a bien été ajouter au panier");
        return redirect()->route('cart.show');
    }


}
