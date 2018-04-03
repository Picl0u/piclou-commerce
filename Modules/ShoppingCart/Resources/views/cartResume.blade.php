<div class="table-cart">

    <div class="cart-title">{{ trans('shop.cart') }}</div>

    <div class="row gutters align-middle table-line">
        @foreach(Cart::instance('shopping')->content() as $row)
            <div class="col col-2 cart-image table-row">
                <img src="{{ resizeImage($row->options->image,30,30) }}" alt="{{ $row->name }}">
            </div>
            <div class="col col-8 cart-product table-row">
                {{ $row->name }} - {{ $row->id }} - x {{ $row->qty }}<br>
                {{ $row->options->declinaison }}
            </div>
            <div class="col col-2 cart-price table-row">
                {{ priceFormat($row->total) }}
            </div>
        @endforeach
    </div>
    <div class="row gutters">
        <div class="col col-10 text-right tfoot">
            <strong>{{ trans('shop.subTotal') }} :</strong>
        </div>
        <div class="col col-2 tfoot">
            {{ Cart::instance('shopping')->subtotal() }}&euro;
        </div>
    </div>
    <div class="row gutters">
        <div class="col col-10 text-right tfoot">
            <strong>{{ trans('shop.vat') }}(20%) :</strong>
        </div>
        <div class="col col-2 tfoot">
            {{ Cart::instance('shopping')->tax() }}&euro;
        </div>
    </div>

    @php $shipping = priceCarrier(); @endphp
    @php $coupon = checkCoupon(); @endphp
    @if(!empty($coupon))
        <div class="row gutters border-top">
            <div class="col col-10 text-right tfoot">
                <strong>Réduction :</strong>
            </div>
            <div class="col col-2 tfoot">
                -{{ priceFormat($coupon['reduceDiff']) }}
            </div>
        </div>
          @php $shipping['total'] -= $coupon['reduceDiff']; @endphp
    @endif

    <div class="row gutters">
        <div class="col col-10 text-right tfoot">
            <strong>{{ trans('shop.shipping') }} :</strong>
        </div>
        <div class="col col-2 tfoot">
            {{ priceFormat($shipping['priceCarrier']) }}
        </div>
    </div>
    <div class="row gutters">
        <div class="col col-10 text-right tfoot">
            <strong>{{ trans('shop.total') }} :</strong>
        </div>
        <div class="col col-2 tfoot">
            {{ priceFormat($shipping['total']) }}
        </div>
    </div>
</div>
