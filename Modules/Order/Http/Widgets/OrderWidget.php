<?php

namespace Modules\Order\Http\Widgets;

use App\Http\Picl0u\AdminWidgetInterface;
use ConsoleTVs\Charts\Facades\Charts;
use Modules\Order\Entities\Order;

class OrderWidget implements AdminWidgetInterface
{
    public function render()
    {
        $orders = Order::select('price_ttc','created_at')
            ->where('created_at','like', date('Y') . '%')
            ->get();
        $count = [];
        $total = [];
        for ($i = 1; $i<=12; $i++){
            $total[$i] = 0;
            $count[$i] = 0;
            foreach ($orders as $order) {
                if($order->created_at->format('m') == $i){
                    $total[$i] += $order->price_ttc;
                    $count[$i] += 1;
                }
            }
        }

        $chart = Charts::multi('line', 'highcharts')
            ->title('Commandes ' .date('Y'))
            ->colors(['#2ab27b', '#3097D1'])
            ->labels([
                'Janvier',
                'Février',
                'Mars',
                'Avril',
                'Mai',
                'Juin',
                'Juillet',
                'Août',
                'Septembre',
                'Octobre',
                'Novembre',
                'Décembre',
            ])
            ->dataset('Nombre de commande', $count)
            ->dataset('Prix total', $total);

        $total = Order::where('created_at','like', date('Y-')."%")->count();
        $prices = Order::where('created_at','like', date('Y-')."%")->sum('price_ttc');

        return view(
            "order::admin.widget.order",
            compact('chart','total','prices')
        );
    }


}