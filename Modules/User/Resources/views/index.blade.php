@extends('layouts.app')

@section('seoTitle')
    {{ __('user::user.my_account') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('user::user.my_account') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')

    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('user::user.my_account') }}</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))
    <div class="account-section">

        <div class="l-container">

            <div class="sidebar show-sm">
                @include("user::navigation")
            </div>

            <div class="row gutters hide-sm">
                <div class="col col-3 account-link">
                    <a href="{{ route('user.infos') }}">
                        <i class="fas fa-user"></i>
                        {{ __('user::user.my_informations') }}
                    </a>
                </div>
                <div class="col col-3 account-link">
                    <a href="{{ route('user.addresses') }}">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ __('user::user.my_addresses') }}
                    </a>
                </div>
                <div class="col col-3 account-link">
                    <a href="{{ route('order.index') }}">
                        <i class="fas fa-shopping-bag"></i>
                        {{ __('user::user.my_orders') }}
                    </a>
                </div>
                <div class="col col-3 account-link">
                    <a href="{{ route('whishlist.index') }}">
                        <i class="fas fa-heart"></i>
                        {{ __('user::user.my_whishlist') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
