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

                    <div class="title">
                        <h2>{{ __('user::user.my_orders') }}</h2>
                    </div>
                    <div class="orders">

                        <table class="bordered striped" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('order::front.order_ref') }}</th>
                                    <th>{{ __('order::front.order_delivery') }}</th>
                                    <th>{{ __('order::front.order_product_number') }}</th>
                                    <th>{{ __('order::front.order_total') }}</th>
                                    <th>{{ __('order::front.order_state') }}</th>
                                    <th>{{ __('order::front.order_date') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            {{ $order->reference }}
                                        <td>
                                            {{ $order->delivery_address }} {{ $order->delivery_additionnal_address }}<br>
                                            {{ $order->delivery_zip_code }} {{ $order->delivery_city }}<br>
                                            {{ $order->delivery_country_name }}
                                        </td>
                                        <td>
                                            {{ $order->total_quantity }}
                                        </td>
                                        <td>
                                            {{ priceFormat($order->price_ttc) }}
                                        </td>
                                        <td>
                                            <span class="label"
                                                <?=
                                                    (!empty($order->Status->color))?
                                                        'style="background-color:'.$order->Status->color.'; color:#FFF;"'
                                                        :'';
                                                ?>
                                            >
                                                {{ $order->Status->getTranslation('name', config('app.locale')) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $order->created_at->format('d/m/Y à H:i') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('order.invoice',['uuid' => $order->uuid]) }}">
                                                <i class="fas fa-file-pdf"></i>
                                                {{ __('order::front.order_invoice') }}
                                            </a>
                                            <a href="{{ route('order.show',['uuid' => $order->uuid]) }}">
                                                <i class="fas fa-eye"></i>
                                                {{ __('order::front.order_view') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
