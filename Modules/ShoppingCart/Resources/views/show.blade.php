@extends('layouts.app')

@section('seoTitle')
    {{ trans('shop.cart') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ trans('shop.cart') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')

    <div class="head-title">
        <div class="l-container">
            <h1>{{ trans('shop.cart') }}</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))

    <div class="cart" data-url="{{ route('cart.product.edit') }}">
        <div class="l-container">
            @include('shoppingcart::cart')
        </div>
    </div>
@endsection