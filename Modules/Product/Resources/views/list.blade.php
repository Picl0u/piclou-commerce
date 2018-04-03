@extends('layouts.app')

@section('seoTitle')
    @if(!empty($category->seo_title))
{{ $category->getTranslation('seo_title', config('app.locale')) }}@if(isset($_GET['page'])) - Page {{ $_GET['page'] }}@endif - {{ Setting('generals.seoTitle') }}
    @else
{{ $category->getTranslation('name', config('app.locale')) }}@if(isset($_GET['page'])) - Page {{ $_GET['page'] }}@endif - {{ Setting('generals.seoTitle') }}
    @endif
@endsection

@section('seoDescription')
    @if(!empty($category->seo_description))
{{ $category->getTranslation('seo_description', config('app.locale')) }} - {{ Setting('generals.seoDescription') }}
    @else
{{ $category->getTranslation('name', config('app.locale')) }} - {{ Setting('generals.seoDescription') }}
    @endif
@endsection

@section('content')
    <div class="head-title">
        <div class="l-container">
            <h1>{{ $category->getTranslation('name', config('app.locale')) }} @if(isset($_GET['page'])) - Page {{ $_GET['page'] }}@endif</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))

    <div class="l-container products-section">
        <div class="row gutters">
            <div class="col col-2 sidebar">
                <h2>

                    @if(!empty($parent->name))
                        <a href="{{ route('product.list',['slug' => $parent->slug, 'id' => $parent->id]) }}">
                            {{ $parent->getTranslation('name', config('app.locale')) }}
                        </a>
                    @else
                        <a href="{{ route('product.list',['slug' => $category->slug, 'id' => $category->id]) }}">
                            {{ $category->getTranslation('name', config('app.locale')) }}
                        </a>
                    @endif
                </h2>
                <nav class="sidebar-navigation">
                    @if(!empty($parent->id))
                    {!!
                        nestableExtends($categories)
                        ->parent($parent->id)
                        ->route(['product.list' => 'slug'])
                        ->renderAsHtml()
                    !!}
                    @else
                        {!!
                           nestableExtends($categories)
                           ->parent($category->id)
                           ->route(['product.list' => 'slug'])
                           ->renderAsHtml()
                        !!}
                    @endif
                </nav>
            </div>
            <div class="col col-10 product-list">

                @if(!is_null($category->imageList) && !empty($category->imageList))
                    <div class="category-image">
                        <img src="{{ asset($category->imageList) }}" alt="{{ $category->name }}" class="hide-sm category-img">
                    </div>
                @elseif(!empty($parent->imageList))
                    <div class="category-image">
                        <img src="{{ asset($parent->imageList) }}" alt="{{ $parent->name }}" class="hide-sm category-img">
                    </div>
                @endif

                <div class="filters-products">
                    <div class="total-product">
                        {{ trans('shop.thereIs') }}
                        {{ $products->total() }}
                        @if($products->total() > 1)
                            {{ trans('shop.products') }}
                        @else
                            {{ trans('shop.product') }}
                        @endif
                    </div>
                    <form method="get"
                          action="{{ route('product.list',[
                          'slug' => $category->getTranslation('slug', config('app.locale')),
                          'id' => $category->id
                          ]) }}"
                     >
                        {{ Form::label('orderField', trans('shop.orderBy')) }}
                        {{ Form::select(
                            'orderField',
                            [
                                'pertinence' => trans('shop.pertinence'),
                                'name_asc' => trans('shop.nameAsc'),
                                'name_desc' => trans('shop.nameDesc'),
                                'price_asc' => trans('shop.priceAsc'),
                                'price_desc' => trans('shop.priceDesc'),
                            ],
                            $order
                        ) }}
                    </form>
                </div>

                <div class="row gutters">
                    @foreach($products as $product)
                        <div class="col col-3">
                            @cache("product::cache.product",compact('product'), null, $product->id."_".config('app.locale'))
                        </div>
                    @endforeach
                </div>

                {{ $products->links() }}

            </div>
        </div>
    </div>
@endsection