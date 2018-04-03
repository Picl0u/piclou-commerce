@extends('layouts.app')

@section('seoTitle')
    {{ __('user::user.my_orders') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('user::user.my_orders') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')
    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('user::user.my_orders') }}</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))

    <div class="account-section">
        <div class="l-container">
            <div class="row gutters">
                <div class="col col-2 sidebar">
                    @include("user::navigation")
                </div>
                <div class="col col-10">

                    @if(count($errors) > 0)
                        <div class="message error" data-component="message">
                            <h5 style="color:#fff">{{ __("order::front.warning") }}</h5>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <span class="close small"></span>
                        </div>
                    @endif

                    <div class="title">
                        <h2>{{ __("order::front.order") }} : {{ $order->reference }}</h2>
                        <a href="{{ route('order.index') }}">
                            <i class="fas fa-chevron-left"></i>
                            {{ __('generals.return') }}
                        </a>
                    </div>
                    <div class="order">
                        <div class="order-title">
                            {{ __("order::front.order") }} : <strong>{{ __("order::front.order_number") }}{{ $order->id }}</strong>
                            - {{ __("order::front.order_ref") }} <strong>{{ $order->reference }}</strong>
                            - {{ __("order::front.order_created") }} <strong>{{ $order->created_at->format('d/m/Y à H:i') }}</strong>
                        </div>

                        <a href="{{ route('order.invoice',['uuid' => $order->uuid]) }}" class="invoice-link">
                            <i class="fas fa-file-pdf"></i> {{ __("order::front.order_invoice") }}
                        </a>

                        <div class="title">
                            <h2>{{ __("order::front.order_history") }}</h2>
                        </div>

                        <div class="order-history">
                            @foreach($order->OrdersStatus as $history)
                                <div class="history">
                                    <div class="label"
                                    <?= (!empty($history->Status->color))?' style="background-color:'.$history->Status->color.';color:#FFF"':''; ?>
                                    >
                                        {{ $history->Status->getTranslation('name',config('app.locale')) }}
                                    </div>
                                    <div class="date">
                                        {{ __("order::front.the") }} {{ $history->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <div class="carrier">
                            <div class="title">
                                <h3>{{ __("order::front.order_carrier") }}</h3>
                            </div>
                            <div class="carrier-infos">
                                {{ $order->shipping_name }} -
                                {{ __("order::front.order_delay") }} : {{ $order->shipping_delay }}
                                @if(!empty($order->shipping_order_id))
                                     - <a href="{{ $order->shipping_url.$order->shipping_order_id }}" target="_blank">
                                        {{ __("order::front.order_shipping_id") }} : {{ $order->shipping_order_id }}
                                    </a>
                                @endif
                            </div>

                        </div>

                        <div class="row gutters">

                            <div class="col col-6 address">
                                <div class="title">
                                    <h3>{{ __("order::front.order_delivery_address") }}</h3>
                                </div>
                                <strong>
                                    {{ $order->delivery_gender }} {{ $order->delivery_firstname }}  {{ $order->delivery_lastname }}
                                </strong><br>
                                {{ $order->delivery_address }}  {{ $order->delivery_additional_address }}<br>
                                {{ $order->delivery_zip_code }} {{ $order->delivery_city }}<br>
                                {{ $order->delivery_country_name }}<br>
                                {{ __("order::front.phone") }} : {{ $order->delivery_phone}}
                            </div>

                            <div class="col col-6 address">
                                <div class="title">
                                    <h3>{{ __("order::front.order_billing_address") }}</h3>
                                </div>
                                <strong>
                                    {{ $order->billing_gender }} {{ $order->billing_firstname }}  {{ $order->billing_lastname }}
                                </strong><br>
                                {{ $order->billing_address }}  {{ $order->billing_additional_address }}<br>
                                {{ $order->billing_zip_code }} {{ $order->billing_city }}<br>
                                {{ $order->billing_country_name }}<br>
                                {{ __("order::front.phone") }} : {{ $order->billing_phone}}
                            </div>

                        </div>

                        <div class="order-detail">
                            <div class="title">
                                <h4>{{ __("order::front.order_detail") }}</h4>
                            </div>
                            <div class="cart">
                                <div class="row gutters align-middle hide-sm">
                                    <div class="col col-6 thead">{{ __("order::front.order_product") }}</div>
                                    <div class="col col-2 thead">{{ __("order::front.order_product_price") }}</div>
                                    <div class="col col-2 thead">{{ __("order::front.order_product_quantity") }}</div>
                                    <div class="col col-2 thead">{{ __("order::front.order_product_price_total") }}</div>
                                </div>
                                @foreach($order->OrdersProducts as $product)
                                    <div class="row gutters align-middle border-top">
                                        <div class="col col-6 tbody">
                                            <strong class="show-sm">{{ ucfirst(__('shop.product')) }}</strong>
                                            <div class="row gutters align-middle">
                                                <div class="col col-2">
                                                    <img src="{{ resizeImage($product->getMedias('image','src'),50,50) }}"
                                                         alt="{{ $product->name }}"
                                                    >
                                                </div>
                                                <div class="col col-10">
                                                    <div class="product-name">{{ $product->name }}</div>
                                                    <div class="product-ref">
                                                        {{ __("order::front.order_ref") }} : {{ $product->ref }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-2 tbody">
                                            <strong class="show-sm">{{ __('shop.unitPrice') }}</strong>
                                            <div class="product-price">{{ priceFormat($product->price_ht) }}</div>
                                        </div>
                                        <div class="col col-2 tbody">
                                            <div class="show-sm">
                                                <strong>{{ __('shop.quantity') }}</strong>
                                            </div>
                                            {{ $product->quantity }}

                                        </div>
                                        <div class="col col-2 tbody">
                                            <strong class="show-sm">{{ __('shop.total') }}</strong>
                                            <div class="product-total">{{ priceFormat($product->price_ttc) }}</div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="row gutters align-middle tfoot">
                                    <div class="col col-10 text-right">{{ __('shop.subTotal') }}</div>
                                    <div class="col col-2">{{ priceFormat($order->price_ht) }}</div>
                                </div>

                                <div class="row gutters align-middle tfoot">
                                    <div class="col col-10 text-right">{{ __('shop.vat') }}({{ $order->vat_percent }}%)</div>
                                    <div class="col col-2">{{ priceFormat($order->vat_price) }}</div>
                                </div>
                                @if(!is_null($order->coupon_price) && !empty($order->coupon_price))
                                    <div class="row gutters align-middle tfoot">
                                        <div class="col col-10 text-right">{{ __("order::front.order_reduce") }} ({{ $order->coupon_name }})</div>
                                        <div class="col col-2">-{{ priceFormat($order->coupon_price) }}</div>
                                    </div>
                               @endif

                                <div class="row gutters align-middle tfoot">
                                    <div class="col col-10 text-right">{{ trans('shop.shipping') }}</div>
                                    <div class="col col-2">{{ priceFormat($order->shipping_price) }}</div>
                                </div>

                                <div class="row gutters align-middle tfoot">
                                    <div class="col col-10 text-right">{{ trans('shop.total') }}</div>
                                    <div class="col col-2">{{ priceFormat($order->price_ttc) }}</div>
                                </div>

                            </div>
                        </div>

                        <div class="order-return">
                            <div class="title">
                                <h5>{{ __("order::front.order_return") }}</h5>
                            </div>

                            {!! Form::open(['route' => ["order.return", $order->uuid]]) !!}

                                <div class="form-item">
                                    @foreach($order->OrdersProducts as $product)
                                        <label class="checkbox">
                                            <input type="checkbox" name="product[]" value="{{ $product->id }}">
                                            {{ $product->ref }} - {{ $product->name }}
                                        </label>
                                    @endforeach
                                </div>

                                <div class="form-item">
                                    {{ Form::label('message', __('order::front.order_return_message')) }}
                                    {{ Form::textarea('message', null) }}
                                </div>

                                <button type="submit">
                                    {{ __("order::front.order_return_send") }}
                                </button>

                            {!! Form::close() !!}

                        </div>

                        <div class="order-return-history">
                            <div class="title">
                                <h5>{{ __("order::front.order_return_history") }}</h5>
                            </div>
                            @foreach($order->OrdersReturns as $return)
                                <div class="return">
                                    <div class="date">
                                        {{ __("order::front.order_return_at") }}
                                        {{ $return->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                    <p>
                                        {{ $return->message }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
