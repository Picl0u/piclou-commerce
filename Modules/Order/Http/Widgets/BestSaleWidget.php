<?php
namespace Modules\Order\Http\Widgets;

use App\Http\Picl0u\AdminWidgetInterface;
use Modules\Order\Entities\OrdersProducts;

class BestSaleWidget implements AdminWidgetInterface
{
    public function render()
    {
        $bestSale = OrdersProducts::selectRaw('*, sum(product_id) as sum')
            ->groupBy('product_id')
            ->orderByRaw('SUM(product_id) DESC')
            ->limit(10)
            ->get();

        return view('order::admin.widget.bestsale',compact('bestSale'));
    }

}