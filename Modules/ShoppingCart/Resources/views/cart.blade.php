@if(Cart::instance('shopping')->total() > 0)
    <div class="table-cart">
        <div class="row gutters align-middle hide-sm">
            <div class="col col-5 thead">{{ __('shop.product') }}</div>
            <div class="col col-2 thead">{{ __('shop.unitPrice') }}</div>
            <div class="col col-2 thead">{{ __('shop.quantity') }}</div>
            <div class="col col-2 thead">{{ __('shop.total') }}</div>
            <div class="col col-1 thead"></div>
        </div>
        @foreach(Cart::instance('shopping')->content() as $row)
            <div class="row gutters align-middle border-top">
                <div class="col col-5 tbody">
                    <strong class="show-sm">{{ ucfirst(__('shop.product')) }}</strong>
                    <div class="row gutters align-middle">
                        <div class="col col-2">
                            <img src="{{ resizeImage($row->options->image,50,50) }}"
                                 alt="{{ $row->name }}"
                            >
                        </div>
                        <div class="col col-10">
                            <div class="product-name">{{ $row->name }}</div>
                            <div class="product-ref">Référence : {{ $row->id }}</div>
                            <div class="product-declinaisons">{{ $row->options->declinaison }}</div>
                        </div>
                    </div>
                </div>
                <div class="col col-2 tbody">
                    <strong class="show-sm">{{ __('shop.unitPrice') }}</strong>
                    <div class="product-price">{{ priceFormat($row->price) }}</div>
                </div>
                <div class="col col-2 tbody" data-id="{{ $row->rowId }}">
                    <div class="show-sm">
                        <strong>{{ __('shop.quantity') }}</strong>
                        <div class="clear"></div>
                    </div>
                    <div class="quantity-cart">
                        <div class="less">-</div>
                        <div class="qty">{{ $row->qty }}</div>
                        <div class="more">+</div>
                    </div>

                </div>
                <div class="col col-2 tbody">
                    <strong class="show-sm">{{ __('shop.total') }}</strong>
                    <div class="product-total">{{ priceFormat($row->total) }}</div>
                </div>
                <div class="col col-1 tbody" data-id="{{ $row->rowId }}">
                    <span class="delete-product">
                        <i class="fas fa-trash"></i>
                    </span>
                </div>
            </div>
        @endforeach
        <div class="row gutters border-top">
            <div class="col col-9 text-right tfoot">
                <strong>{{ __('shop.subTotal') }} :</strong>
            </div>
            <div class="col col-3 tfoot">
                {{ Cart::instance('shopping')->subtotal() }}&euro;
            </div>
        </div>
        <div class="row gutters border-top">
            <div class="col col-9 text-right tfoot">
                <strong>{{ trans('shop.vat') }}({{ config('cart.tax') }}%) :</strong>
            </div>
            <div class="col col-3 tfoot">
                {{ Cart::instance('shopping')->tax() }}&euro;
            </div>
        </div>

        <div class="row gutters coupon-cart border-top">
            <div class="col col-9 text-right tfoot">
                <strong>
                    Code promo
                </strong>
            </div>
            <div class="col col-3 tfoot">
                {!! Form::open(['route' => 'cart.coupon']) !!}
                    <div class="form-item">
                        <div class="append">
                            <input type="text"
                                   name="coupon"
                                   value="{{ (!empty($coupon))?$coupon['name']:'' }}"
                                   required="required"
                            >
                            <button class="button outline"><i class="fas fa-check"></i></button>
                        </div>
                    </div>
                {!! Form::close() !!}
                @if(!empty($coupon))
                    <a href="{{ route('cart.coupon.cancel') }}" class="small">Annuler</a>
                @endif
            </div>
        </div>

        @if(!empty($coupon))
            <div class="row gutters border-top">
                <div class="col col-9 text-right tfoot">
                    <strong>Réduction :</strong>
                </div>
                <div class="col col-3 tfoot">
                    -{{ priceFormat($coupon['reduceDiff']) }}
                </div>
            </div>
        @endif

        <div class="row gutters border-top">
            <div class="col col-9 text-right tfoot">
                <strong>{{ __('shop.shipping') }} :</strong>
            </div>
            <div class="col col-3 tfoot">
                {{ priceFormat($shippingPrice) }}
            </div>
        </div>

        <div class="row gutters border-top">
            <div class="col col-9 text-right tfoot">
                <strong>{{ __('shop.total') }} :</strong>
            </div>
            <div class="col col-3 tfoot">
                {{ priceFormat($total) }}
            </div>
        </div>

        <div class="cart-links">
            <a href="/">
                <i class="fas fa-arrow-left"></i>
                {{ __('shop.continueShopping') }}
            </a>
            <div>
                <a href="{{ route('cart.user.connect') }}">
                    <i class="fas fa-check"></i>
                    {{ __('shop.order') }}
                </a>
                @if(!empty(setting('orders.freeShippingPrice')))
                    @if(Cart::instance('shopping')->total(2,".","") < setting('orders.freeShippingPrice'))
                        <p>
                            Encore
                            <strong>
                            {{ priceFormat(setting('orders.freeShippingPrice') - Cart::instance('shopping')->total()) }}
                            </strong>
                            pour bénéficier des frais de port offert.
                        </p>
                    @endif
                @endif
            </div>
        </div>

    </div>
@else
    <h2>Votre panier est vide</h2>
    <div class="cart-links">
        <a href="/">
            <i class="fas fa-arrow-left"></i>
            {{ __('shop.continueShopping') }}
        </a>
    </div>
@endif