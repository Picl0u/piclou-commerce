@extends('layouts.app')

@section('seoTitle')
    {{ __('shop.cart') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('shop.cart') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')

    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('shop.recap') }}</h1>
        </div>
    </div>

    <div class="cart">
        <div class="l-container">
            <div class="row gutters">
                <div class="col col-6 address">
                    <div class="title">
                        <h1>{{ trans('user.delivery_address') }}</h1>
                    </div>
                    <strong>
                        {{ $address['delivery']['firstname'] }} {{ $address['delivery']['lastname'] }}
                    </strong><br>
                    {{ $address['delivery']['address'] }}<br>
                    @if(!empty($address['delivery']['additional_address'] ))
                        {{ $address['delivery']['additional_address'] }}<br>
                    @endif
                    {{ $address['delivery']['zip_code'] }} -
                    {{ $address['delivery']['city'] }} -
                    {{ $address['delivery']['country_name'] }}<br>
                    {{ __('form.phone') }} : {{ $address['billing']['phone'] }}
                </div>
                <div class="col col-6 address">
                    <div class="title">
                        <h1>{{ __('user.billing_address') }}</h1>
                    </div>
                    <strong>
                        {{ $address['billing']['firstname'] }} {{ $address['billing']['lastname'] }}
                    </strong><br>
                    {{ $address['billing']['address'] }}<br>
                    @if(!empty($address['billing']['additional_address'] ))
                        {{ $address['billing']['additional_address'] }}<br>
                    @endif
                    {{ $address['billing']['zip_code'] }} -
                    {{ $address['billing']['city'] }} -
                    {{ $address['billing']['country_name'] }}<br>
                    {{ __('form.phone') }} : {{ $address['billing']['phone'] }}
                </div>
            </div>

            <div class="table-cart recap-table">
                <div class="row gutters align-middle hide-sm">
                    <div class="col col-6 thead">{{ __('shop.product') }}</div>
                    <div class="col col-2 thead">{{ __('shop.unitPrice') }}</div>
                    <div class="col col-2 thead">{{ __('shop.quantity') }}</div>
                    <div class="col col-2 thead">{{ __('shop.total') }}</div>
                </div>
                @foreach(Cart::instance('shopping')->content() as $row)
                    <div class="row gutters align-middle border-top">
                        <div class="col col-6 tbody">
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
                                    <div class="product-declinaison">{{ $row->options->declinaison }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-2 tbody">
                            <strong class="show-sm">{{ __('shop.unitPrice') }}</strong>
                            <div class="product-price">{{ priceFormat($row->price) }}</div>
                        </div>
                        <div class="col col-2 tbody">
                            <div class="show-sm">
                                <strong>{{ __('shop.quantity') }}</strong>
                            </div>
                            {{ $row->qty }}
                        </div>
                        <div class="col col-2 tbody">
                            <strong class="show-sm">{{ __('shop.total') }}</strong>
                            <div class="product-total">{{ priceFormat($row->total) }}</div>
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
                        <strong>{{ __('shop.vat') }}({{ config('cart.tax') }}%) :</strong>
                    </div>
                    <div class="col col-3 tfoot">
                        {{ Cart::instance('shopping')->tax() }}&euro;
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
                        {{ priceFormat($shipping['price']) }}
                    </div>
                </div>
                <div class="row gutters border-top">
                    <div class="col col-9 text-right tfoot">
                        <strong>{{ __('shop.total') }} :</strong>
                    </div>
                    <div class="col col-3 tfoot">
                        {{ priceFormat($total + $shipping['price']) }}
                    </div>
                </div>

            </div>
            @if(!empty(setting('orders.cgv')))
            <div class="cgv">
                En procèdant au paiement de votre commande, vous acceptez les
                <a href="#cgv-modal">conditions générales de vente</a>
            </div>
            @endif
            <div class="payment-button">
                <a href="{{ route('cart.process') }}">
                    {{ __('shop.pay_order') }}
                </a>
                <img src="/images/credit-card-icons-png.png" alt="Moyens de paiements">
            </div>

        </div>
    </div>

    @if(!empty(setting('orders.cgv')))
        <div class="remodal" data-remodal-id="cgv-modal">
            <div data-remodal-action="close" class="remodal-close"></div>
            <div class="cgv-content">
                <h3>{{ $cgv->name }}</h3>
                {!! $cgv->getTranslation('description', config('app.locale')) !!}
            </div>
        </div>
    @endif

@endsection