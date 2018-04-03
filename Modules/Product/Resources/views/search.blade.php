@extends('layouts.app')

@section('seoTitle')
    Recherche : {{ $keywords }}@if(isset($_GET['page'])) - Page {{ $_GET['page'] }}@endif - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    Recherche : {{ $keywords }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')
    <div class="head-title">
        <div class="l-container">
            <h1>Recherche : {{ $keywords }} @if(isset($_GET['page'])) - Page {{ $_GET['page'] }}@endif</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))

    <div class="l-container products-section">
        <div class="product-list">
            <div class="filters-products">
                <div class="total-product">
                    {{ __('shop.thereIs') }}
                    {{ $products->total() }}
                    @if($products->total() > 1)
                        {{ trans('shop.products') }}
                    @else
                        {{ trans('shop.product') }}
                    @endif
                </div>

            </div>

            <div class="row gutters">
                @foreach($products as $product)
                    <div class="col col-3">
                        @cache("product::cache.product",compact('cache.product'), null, $product->id."_".config('app.locale'))
                    </div>
                @endforeach
            </div>

            {{ $products->links() }}

        </div>
    </div>
@endsection