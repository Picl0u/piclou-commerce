@extends('layouts.app')

@section('seoTitle')
    {{ __('user.login') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('user.login') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')
    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('form.login') }}</h1>
        </div>
    </div>
    <div class="l-container">
        <div class="login-register-section">
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

            @include('users.login')
            <hr>
            <div class="links">
                <p>
                    {{ __('user.no_account_yet') }} <a href="{{ route('register') }}">{{ __('user.register_link') }}</a>
                </p>
            </div>
        </div>
    </div>
@endsection
