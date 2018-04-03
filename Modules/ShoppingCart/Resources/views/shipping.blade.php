@extends('layouts.app')

@section('seoTitle')
    {{ trans('shop.cart') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ trans('shop.cart') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')

    <div class="order-process">
        <div class="l-container">

            <div class="row gutters">

                <div class="col col-8 process-container">

                    <div class="timeline check">
                        <h2><i class="fas fa-check"></i> 1. {{ trans("user.personal_informations") }}</h2>
                    </div>

                    <div class="timeline check">
                        <h2><i class="fas fa-check"></i> 2. {{ trans('shop.address') }}</h2>
                    </div>

                    <h2>3. {{ trans("shop.shipping_method") }}</h2>
                    @if(count($errors) > 0)
                        <div class="message error" data-component="message">
                            <h5 style="color:#fff">Attention !</h5>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <span class="close small"></span>
                        </div>
                    @endif

                    <div class="carriers">
                        {!! Form::open(['route' => 'cart.user.shipping.store']) !!}
                            @if(!is_null($carriers))
                                @foreach($carriers as $transport)
                                    <div class="carrier">
                                        <label class="checkbox">
                                            <input type="radio"
                                                   name="carrier_id"
                                                   value="{{ $transport->carriers_id }}"
                                            >
                                            <span class="carrier-infos">
                                                @if(!empty($transport->Carrier->image))
                                                    <img src="{{ resizeImage($transport->Carrier->image, null, 50) }}"
                                                         alt="{{ $transport->Carrier->name }}"
                                                    >
                                                @endif
                                                <strong>{{ $transport->Carrier->name }}</strong> -
                                                {{ trans('shop.shipping_delay') }} : {{ $transport->Carrier->delay }} -
                                                <strong>
                                                    @if(!empty(setting('orders.freeShippingPrice')))
                                                        @if($total >= setting('orders.freeShippingPrice'))
                                                            {{ trans('shop.free_shipping') }}
                                                        @else
                                                            {{ trans('shop.price') }} :
                                                            {{ priceFormat($transport->price) }}
                                                        @endif
                                                    @else
                                                        {{ trans('shop.price') }} :
                                                        {{ priceFormat($transport->price) }}
                                                    @endif
                                                </strong>
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <div class="carrier">
                                    <label class="checkbox">
                                        <input type="radio"
                                               name="carrier_id"
                                               value="{{ $carrier->id }}"
                                        >
                                        <span class="carrier-infos">
                                            @if(!empty($carrier->image))
                                                <img src="{{ resizeImage($carrier->image, null, 50) }}"
                                                     alt="{{ $carrier->name }}"
                                                >
                                            @endif
                                            <strong>{{ $carrier->name }}</strong> -
                                            {{ trans('shop.shipping_delay') }} : {{ $carrier->delay }}  -
                                            <strong>
                                                @if(!empty(setting('orders.freeShippingPrice')))
                                                    @if($total >= setting('orders.freeShippingPrice'))
                                                        {{ trans('shop.free_shipping') }}
                                                    @else
                                                        {{ trans('shop.price') }} :
                                                        {{ priceFormat($carrier->default_price) }}
                                                    @endif
                                                @else
                                                    {{ trans('shop.price') }} :
                                                    {{ priceFormat($carrier->default_price) }}
                                                @endif
                                            </strong>
                                        </span>
                                    </label>
                                </div>
                            @endif

                            <button type="submit">
                                {{ trans('form.continue') }}
                            </button>

                            {!! Form::close() !!}
                    </div>

                    <div class="timeline">
                        <h2>4. {{ trans("shop.payment") }}</h2>
                    </div>

                </div>

                <div class="col col-4">
                    @include("shoppingcart::cartResume")
                </div>

            </div>
        </div>
    </div>

@endsection