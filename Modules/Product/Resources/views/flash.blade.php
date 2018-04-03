@extends('layouts.app')

@section('seoTitle')
    {{ __('generals.flashSales') }}@if(isset($_GET['page'])) - Page {{ $_GET['page'] }}@endif - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('generals.flashSales') }}}} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')
    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('generals.flashSales') }} @if(isset($_GET['page'])) - Page {{ $_GET['page'] }}@endif</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))

    <div class="l-container products-section">
        <div class="product-list">
            <div class="title">
                <h3>{{ __('shop.last_days') }}</h3>
            </div>
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
                <form method="post"
                      action="{{ route('product.flash') }}"
                >
                    {{ Form::label('orderField', __('shop.orderBy')) }}
                    {{ Form::select(
                        'orderField',
                        [
                            'pertinence' => __('shop.pertinence'),
                            'name_asc' => __('shop.nameAsc'),
                            'name_desc' => __('shop.nameDesc'),
                            'price_asc' => __('shop.priceAsc'),
                            'price_desc' => __('shop.priceDesc'),
                        ],
                        $order
                    ) }}
                </form>
            </div>

            @foreach($products as $product)
                @php
                    $productLink = route('product.show',[
                        'slug' => $product->getTranslation('slug', config('app.locale')),
                        'id' => $product->id
                    ]);
                @endphp
                <div class="row gutters align-middle product-flash">
                    <div class="col col-2">
                        @if(!empty($product->image))
                            <a href="{{ $productLink }}">
                                <img
                                    src="{{ resizeImage(str_replace('\\', '/',$product->getMedias('image','src')), null, 280) }}"
                                    alt="{{ $product->getMedias('image','alt') }}"
                                >
                            </a>
                        @endif
                    </div>
                    <div class="col col-3">
                        <div class="countdown"
                             data-date="{{ formatDate($product->reduce_date_end,'Y-m-d H:i:s')}}"
                        >
                            <div class="count days">
                                <p>{{ trans('shop.days') }}</p>
                                <span>0</span>
                            </div>
                            <div class="count hours">
                                <p>{{ trans('shop.hours') }}</p>
                                <span>0</span>
                            </div>
                            <div class="count minutes">
                                <p>{{ trans('shop.minutes') }}</p>
                                <span>0</span>
                            </div>
                            <div class="count seconds">
                                <p>{{ trans('shop.seconds') }}</p>
                                <span>0</span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="col col-3">
                        <div class="product-categories">
                            @foreach($product->ProductsHasCategories as $category)
                                <a href="{{ route('product.list',[
                                    'slug' => $category->ShopCategory->getTranslation('slug', config('app.locale')),
                                    'id' => $category->id
                                ]) }}">
                                    {{ $category->ShopCategory->getTranslation('name', config('app.locale')) }}
                                </a>
                            @endforeach
                        </div>
                        <h3>
                            <a href="{{ $productLink }}">
                                {{ $product->getTranslation('name', config('app.locale')) }}
                            </a>
                        </h3>
                        <div class="product-summary">
                            {!! $product->getTranslation('summary', config('app.locale')) !!}
                        </div>
                    </div>

                    <div class="col col-3">
                        <div class="product-prices">
                            @if(!empty($product->reduce_price))
                                {{ priceFormat($product->price_ttc - $product->reduce_price) }}
                            @endif
                            @if(!empty($product->reduce_percent))
                                {{ priceFormat(
                                    $product->price_ttc -
                                    ($product->price_ttc * (($product->reduce_percent/100)))
                                ) }}
                            @endif
                            <span>{{ priceFormat($product->price_ttc) }}</span>
                        </div>
                        <div class="product-stock">
                            {{ __('shop.stillAvailable') }} : {{ $product->stock_available }}
                        </div>
                    </div>
                    <div class="col col-1">
                        <a href="{{ $productLink }}" class="see-product">
                            <i class="fas fa-arrow-right"></i>
                            {{ __('shop.see_product') }}
                        </a>
                    </div>
                </div>
            @endforeach

            {{ $products->links() }}

        </div>
    </div>

    @if($contents)
        <div class="home-contents">
            <div class="l-container">
                <div class="row gutters auto align-center">
                    @foreach($contents as $content)
                        <div class="col">
                            @cache('content::cache.home',compact('content'),null, $content->id)
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

@endsection