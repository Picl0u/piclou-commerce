@extends('layouts.app')

@section('seoTitle')
    {{ __('user.create_account') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('user.create_account') }} - {{ Setting('generals.seoDescription') }}
@endsection


@section('content')
<div class="head-title">
    <div class="l-container">
        <h1>{{ __('user.create_account') }}</h1>
    </div>
</div>
<div class="login-register-section">

    <div class="l-container">
        <div class="login-section">

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

            @include('users.register')
        </div>
        <hr>
        <div class="links">
            <p>
                {{ __('user.account_yet') }} <a href="{{ route('login') }}">{{ __('user.login_link') }}</a>
            </p>
        </div>
    </div>
</div>
@endsection
