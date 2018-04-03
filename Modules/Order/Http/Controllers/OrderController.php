<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Emails\ReturnOrder;
use Modules\Order\Entities\Order;
use Modules\Order\Http\Requests\OrderReturn as OrderReturnRequest;
use Modules\Order\Entities\OrderReturn;
use Ramsey\Uuid\Uuid;

class OrderController extends Controller
{
    protected $viewPath = 'order::';

    /**
     * Liste des commandes
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('id','DESC')->get();

        $arianne = [
            __('generals.home') => '/',
            __('user::user.my_account') => route('user.account'),
            __('user::user.my_orders') => route('order.index')
        ];

        return view($this->viewPath . 'index', compact('orders', 'arianne'));

    }

    /**
     * Force le téléchargement de la facture
     * @param string $uuid
     * @return mixed
     */
    public function invoice(string $uuid)
    {
        $order = Order::select('reference')->where('uuid', $uuid)
            ->where('user_id', Auth::user()->id)
            ->firstorFail();

        $name = config('ikCommerce.invoicePath') . "/" .
            config('ikCommerce.invoiceName') . "-" .
            $order->reference . '.pdf';
        return Storage::download($name);
    }

    /**
     * Voir le détail d'une commande
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $uuid)
    {
        $order = Order::where('uuid', $uuid)
            ->where('user_id', Auth::user()->id)
            ->firstorFail();

        $arianne = [
            __('generals.home') => '/',
            __('user::user.my_account') => route('user.account'),
            __('user::user.my_orders') => route('order.index'),
            __('order::front.order')." : ".$order->reference => route('order.show',['uuid' => $order->uuid]),
        ];

        return view($this->viewPath . "show", compact('order','arianne'));
    }

    public function returnProducts(OrderReturnRequest $request ,string $uuid)
    {
        $order = Order::where('uuid', $uuid)
            ->where('user_id', Auth::user()->id)
            ->firstorFail();

        $insertReturn = [
            'uuid' => Uuid::uuid4()->toString(),
            'order_id' => $order->id,
            'user_id' => Auth::user()->id,
            'message' => $request->message
        ];

        Mail::to($order->user_email)
            ->send(new ReturnOrder($order, $request->message));

        Mail::to(setting('generals.email'))
            ->send(new ReturnOrder($order, $request->message));

        if(isset($request->product) && !empty($request->product)) {
            foreach($request->product as $product) {
                $insertReturn['orders_product_id'] = $product;
                OrderReturn::create($insertReturn);
            }
        }else{
            OrderReturn::create($insertReturn);
        }

        session()->flash('success','Merci, votre retour a bien été envoyé.');
        return redirect()->route('order.show',['uuid' => $order->uuid]);

    }

}
