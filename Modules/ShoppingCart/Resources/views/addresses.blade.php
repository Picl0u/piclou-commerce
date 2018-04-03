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

                    <h1>2. {{ trans('shop.address') }}</h1>

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

                    <div class="order-addresses">
                        @if(!empty($addressList))
                            {!! Form::open(['route' => 'cart.user.address.select']) !!}

                                <div class="addresses-choose delivery-address">
                                    <div class="title">
                                        <h2>{{ trans('user.delivery_addresses') }}</h2>
                                    </div>
                                    <div class="row gutters">
                                        <?php $i = 0; ?>
                                        @foreach($addressList as $address)
                                            @if(!empty($address['delivery']))
                                                <div class="col col-4 form-item">
                                                    <label class="checkbox">
                                                        <input type="radio"
                                                               name="delivery_address"
                                                               value="{{ $address['uuid'] }}"
                                                               {{ (empty($i)) ? 'checked' : '' }}
                                                        >
                                                        <strong>
                                                            {{ $address['firstname'] }} {{ $address['lastname'] }}
                                                        </strong><br>
                                                        {{ $address['address'] }}<br>
                                                        @if(!empty($address['additional_address']))
                                                            {{ $address['additional_address'] }}<br>
                                                        @endif
                                                        {{ $address['zip_code'] }} -
                                                        {{ $address['city'] }}<br>
                                                        {{ trans('form.phone') }} : {{ $address['phone'] }}<br>
                                                    </label>
                                                </div>
                                                <?php $i++; ?>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <div class="addresses-choose delivery-address">
                                    <div class="title">
                                        <h2>{{ trans('user.billing_addresses') }}</h2>
                                    </div>
                                    <div class="row gutters">
                                        <?php $i = 0; ?>
                                        @foreach($addressList as $key => $address)
                                            @if(!empty($address['billing']))
                                                <div class="col col-4 form-item">
                                                    <label class="checkbox">
                                                        <input type="radio"
                                                               name="billing_address"
                                                               value="{{ $address['uuid'] }}"
                                                               {{ (empty($i)) ? 'checked' : '' }}
                                                        >
                                                        <strong>
                                                            {{ $address['firstname'] }} {{ $address['lastname'] }}
                                                        </strong><br>
                                                        {{ $address['address'] }}<br>
                                                        @if(!empty($address['additional_address']))
                                                            {{ $address['additional_address'] }}<br>
                                                        @endif
                                                        {{ $address['zip_code'] }} -
                                                        {{ $address['city'] }}<br>
                                                        {{ trans('form.phone') }} : {{ $address['phone'] }}<br>
                                                    </label>
                                                </div>
                                                <?php $i++; ?>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                            <button type="submit">
                                {{ trans('form.continue') }}
                            </button>

                            {!! Form::close() !!}

                            <div class="new-address-control">
                                <a href="#">
                                    <i class="fas fa-plus"></i>
                                    {{ trans('form.add_new_address') }}
                                </a>
                            </div>
                            <div class="add-new-address">
                                @include('shoppingcart::formAddress')
                            </div>
                        @else
                            @include('shoppingcart::formAddress')
                        @endif

                    </div>

                    <div class="timeline">
                        <h2>3. {{ trans("shop.shipping_method") }}</h2>
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