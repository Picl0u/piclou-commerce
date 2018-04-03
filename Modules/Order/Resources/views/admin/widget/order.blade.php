<div class="row gutters">

    <div class="col col-6 ">
        <div class="widget primary">
            <div class="widget-title">
                <span>
                <i class="fas fa-shopping-basket"></i>
                </span>
                Panier moyen :  {{ date("Y") }}
            </div>
            <div class="widget-value">
                @if(!empty($total))
                    {{ priceFormat($prices/$total) }} / panier
                @else
                    {{ priceFormat(0) }} / panier
                @endif
            </div>
        </div>
    </div>

    <div class="col col-6">
        <div class="widget warning">
            <div class="widget-title">
                <span>
                    <i class="fas fa-money-bill-alt"></i>
                </span>
                Prix total : {{ date("Y") }}
            </div>
            <div class="widget-value">
                {{ priceFormat($prices) }}
            </div>
        </div>
    </div>

    <div class="col col-12">
        <div class="widget">
            {!! $chart->html() !!}
            {!! $chart->script() !!}
        </div>
    </div>
</div>