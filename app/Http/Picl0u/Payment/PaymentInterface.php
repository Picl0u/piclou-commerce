<?php

namespace App\Http\Picl0u\Payment;

use Illuminate\Http\Request;

interface PaymentInterface
{
    public function process(float $total);

    public function auto(Request $request);

    public function accept();

    public function refuse();
}