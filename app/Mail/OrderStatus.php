<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Status;

class OrderStatus extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var Order
     */
    private $order;
    /**
     * @var Status
     */
    private $status;

    /**
     * OrderStatus constructor.
     * @param Order $order
     * @param Status $status
     */
    public function __construct(Order $order, Status $status)
    {
        //
        $this->order = $order;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->order;
        $status = $this->status;
        $products = $order->OrdersProducts;

        return $this->from(setting('generals.email'), setting('generals.websiteName'))
            ->subject('Votre commande a été mise à jours')
            ->view('order::mail.orderStatus',compact('order', 'status', 'products'));
    }
}
