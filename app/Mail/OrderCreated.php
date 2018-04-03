<?php

namespace App\Mail;

use \Modules\Order\Entities\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var Order
     */
    private $order;
    /**
     * @var array
     */
    private $products;
    /**
     * @var string
     */
    private $invoice;


    /**
     * OrderCreated constructor.
     * @param Order $order
     * @param array $products
     * @param string $invoice
     */
    public function __construct(Order $order, array $products, string $invoice)
    {
        //
        $this->order = $order;
        $this->products = $products;
        $this->invoice = $invoice;
    }


    public function build()
    {
        $order = $this->order;
        $products = $this->products;
        return $this
            ->subject('Votre commande : ' . $order->reference)
            ->attachData(Storage::get($this->invoice),'facture-' . $order->reference . '.pdf', [
                'mime' => 'application/pdf'
            ])
            ->markdown('shoppingcart::mail.orderCreatedHtml', compact('order','products'));
    }
}
