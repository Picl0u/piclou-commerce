<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductQuantityAlert extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var array
     */
    private $products;

    /**
     * ProductQuantityAlert constructor.
     * @param array $products
     */
    public function __construct(array $products)
    {
        //
        $this->products = $products;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $products = $this->products;
        return $this->from(setting('generals.email'), setting('generals.websiteName'))
            ->subject('Alerte quantité produit')
            ->markdown('shoppingcart::mail.alertProductHtml',compact('products'));
    }
}
