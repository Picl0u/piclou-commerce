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
                    <h1>1. {{ trans('user.personal_informations') }}</h1>

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

                    <nav class="tabs" data-component="tabs" data-hash="true">
                        <ul>
                            @if(!empty(setting('orders.noAccount')))
                                <li class="active">
                                    <a href="#order-express">
                                        {{ trans('user.order_guest') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#order-connect">
                                       {{ trans('form.login') }}
                                    </a>
                                </li>
                            @else
                                <li class="active">
                                    <a href="#order-connect">
                                        {{ trans('form.login') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#order-register">
                                        {{ trans('user.create_account') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>

                    @if(!empty(setting('orders.noAccount')))
                        <div id="order-express">

                            {!! Form::open(['route' => 'cart.user.express']) !!}
                                <label>{{ trans('form.civility') }}</label>
                                <div class="form-item form-checkboxes">
                                    <label class="checkbox">
                                        <input type="radio" name="express_gender" value="M" checked="checked">
                                        M.
                                    </label>
                                    <label class="checkbox">
                                        <input type="radio" name="express_gender" value="Mme">
                                        Mme
                                    </label>
                                </div>
                                <div class="form-item">
                                    {{ Form::label('express_firstname', trans('form.firstname')) }}
                                    {{ Form::text('express_firstname', null) }}
                                </div>
                                <div class="form-item">
                                    {{ Form::label('express_lastname', trans('form.lastname')) }}
                                    {{ Form::text('express_lastname', null) }}
                                </div>
                                <div class="form-item">
                                    {{ Form::label('express_email', trans('form.email')) }}
                                    {{ Form::email('express_email', null) }}
                                </div>

                                <p>
                                    <strong>Créez un compte (optionnel)</strong><br>
                                    Et gagnez du temps pour votre prochaine commande.
                                </p>

                                <div class="form-item">
                                    {{ Form::label('express_password', trans('form.password')." - optionnel") }}
                                    {{ Form::password('express_password', null) }}
                                </div>

                                <div class="form-item">
                                    <label class="checkbox">
                                        <input type="checkbox" name="express_newsletter" checked="checked">
                                        Recevoir notre newsletter
                                    </label>
                                    <div class="desc">
                                        Vous pouvez vous désinscrire à tout moment.
                                    </div>
                                </div>

                                <button type="submit">
                                    {{ trans('form.continue') }}
                                </button>
                            {!! Form::close() !!}

                        </div>
                    @endif
                    <div id="order-connect">
                        @include('users.login')
                    </div>
                    @if(empty(setting('orders.noAccount')))
                        <div id="order-register">
                            @include('users.register')
                        </div>
                    @endif

                    <div class="timeline">
                        <h2>2. {{ trans("shop.address") }}</h2>
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