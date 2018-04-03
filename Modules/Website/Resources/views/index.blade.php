@extends('layouts.app')

@section('seoTitle')
    {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ Setting('generals.seoDescription') }}
@endsection

@section('content')
    @if($sliders)
        <div
            class="slider {{ setting('slider.type') }}"
            data-transition="{{ setting('slider.transition') }}"
            data-slideDuration="{{ setting('slider.slideDuration') }}"
            data-transitionDuration="{{ setting('slider.transitionDuration') }}"
            data-arrows="{{ (!empty(setting('slider.arrows')))?'true':'false' }}"
        >
            @foreach($sliders as $slide)
                @cache('slider::cache.home', compact($slide), null, $slide->id."_".config('app.locale'))
            @endforeach
        </div>
    @endif
    @include('includes.searchBar', compact('sliders'))
    @if(!empty($categories))
        <div class="l-container">
            <div class="row gutters align-center categories">
                @foreach($categories as $category)
                    @cache('product::cache.home',compact($category), null, $category->id."_".config('app.locale'))
                @endforeach
            </div>
        </div>
    @endif

    @if(count($bestSale) > 0 || count($flashSales) > 0)
        <div class="l-container">
            <div class="row gutters align-center flash-bests-sales">
                @if(count($bestSale) > 0)
                    <div class="col {{ (count($flashSales) > 0)?'col-6':'col-12' }} best-sales">
                        <div class="title">
                            <h3>{{ __('shop.bestsale') }}</h3>
                            @if(count($bestSale) > 1)
                                <div class="flash-arrows">
                                    <span class="best-prev">
                                        <i class="fas fa-arrow-left"></i>
                                    </span>
                                        <span class="best-next">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="best-sale-slider">
                            @foreach($bestSale as $productOrder)
                                @cache('order::products.bestSale',compact($productOrder), null, $productOrder->Product->id."_".config('app.locale'))
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(count($flashSales) > 0)
                    <div class="col {{ (count($bestSale) > 0)?'col-6':'col-12' }}  flash-sales">
                        <div class="title">
                            <div>
                                <h3>{{ trans("shop.flashSalesTitle") }}</h3>
                                <a href="{{ route('product.flash') }}">{{ __("shop.flashSalesLink") }}</a>
                            </div>
                            @if(count($flashSales) > 1)
                                <div class="flash-arrows">
                                    <span class="flash-prev">
                                        <i class="fas fa-arrow-left"></i>
                                    </span>
                                    <span class="flash-next">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flash-sales-slider">
                            @foreach($flashSales as $product)
                                @cache('product::cache.homeFlash',compact($product),null, $product->id."_".config('app.locale'))
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if(count($weekSelections) > 0)
        <div class="l-container">
            <div class="week-selections">
                <div class="title">
                    <div>
                        <h4>{{ trans("shop.weekSelections") }}</h4>
                    </div>
                    @if(count($weekSelections) > 6)
                        <div class="week-arrows">
                            <span class="week-prev">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            <span class="week-next">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    @endif
                </div>
                <div class="slider-week-sections">
                    @foreach($weekSelections as $product)
                        @cache("product::cache.product",compact('product'), null, $product->id."_".config('app.locale'))
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($newsletter)
        @cache("newsletter::cache.newsletter",compact('newsletter'),null, $newsletter->id."_".config('app.locale'))
    @endif

    @if(count($contents) > 0)
        <div class="home-contents">
            <div class="l-container">
                <div class="row gutters auto align-center ">
                    @foreach($contents as $content)
                        <div class="col">
                            @cache('content::cache.home',compact('content'),null, $content->id."_".config('app.locale'))
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

@endsection
