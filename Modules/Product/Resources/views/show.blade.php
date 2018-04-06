 @extends('layouts.app')

@section('seoTitle')
    @if(!empty($product->seo_title))
        {{ $product->getTranslation('seo_title', config('app.locale')) }} - {{ Setting('generals.seoTitle') }}
    @else
        {{ $product->getTranslation('name', config('app.locale')) }} - {{ Setting('generals.seoTitle') }}
    @endif
@endsection

@section('seoDescription')
    @if(!empty($product->seo_description))
        {{ $product->getTranslation('seo_description', config('app.locale')) }} - {{ Setting('generals.seoDescription') }}
    @else
        {{ $product->getTranslation('name', config('app.locale')) }} - {{ Setting('generals.seoDescription') }}
    @endif
@endsection

 @section('seoImage'){{ asset($product->getMedias('image','src')) }}@endsection

@section('content')
    <div class="head-title">
        <div class="l-container">
            <h1>{{ $product->shopCategory->getTranslation('name', config('app.locale')) }}</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))
    <div class="product-detail">
        <div class="l-container">
            <div class="page-actions">
                <a href="{{ URL::previous() }}" class="return">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('shop.returnList') }}
                </a>
            </div>

            <div class="product">
                <div class="row gutters">
                    <div class="col col-5">
                        <div class="images">
                            @foreach($images as $key => $image)
                                @if(!empty(setting('products.zoomEnable')))
                                    <div class="zoom"
                                         data-url="/{{ $image->getMedias('image','src')}}"
                                         data-img="{{ $key }}">
                                @else
                                    <div class="product-show" data-img="{{ $key }}">
                                @endif
                                @if(!empty(setting('products.modalEnable')))
                                    <a href="#product-image-modal" class="product-image-modal">
                                @endif
                                    <img src="{{ asset($image->getMedias('image','src')) }}"
                                         alt="{{ $image->getMedias('image','alt') }}"
                                    >
                                @if(!empty(setting('products.modalEnable')))
                                    </a>
                                @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col col-1">
                        <div class="products-col">
                            <div class="socials">
                                <a href="{{ route('whishlist.product.add') }}"
                                   data-product="{{ $product->uuid }}"
                                   class="add-to-whishlist"
                                >
                                    <i class="far fa-heart"></i>
                                </a>
                                <a href="mailto:?subject={{ $product->getTranslation('name',config('app.locale')) }} - {{ setting("generals.websiteName") }}&amp;body={{ $product->getTranslation('name',config('app.locale')) }} : {{ url()->current() }}">
                                    <i class="far fa-envelope"></i>
                                </a>
                            </div>
                            <div class="vignettes">
                                @foreach($images as $key => $image)
                                    <img
                                        src="{{ asset(resizeImage($image->getMedias('image','src'), 63, 63)) }}"
                                        alt="{{ $image->getMedias('image','alt') }}"
                                        data-img="{{ $key }}"
                                    >
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col col-6 ">
                        <div class="product-infos">
                            <div class="infos">

                                <h2>{{ $product->getTranslation('name', config('app.locale')) }}</h2>
                                <div class="summary">
                                    {!! $product->getTranslation('summary', config('app.locale')) !!}
                                </div>

                                <div class="product-prices">
                                    @if(!empty($product->reduce_price) || !empty($product->reduce_percent))
                                        @if(is_null($product->reduce_date_begin) || $product->reduce_date_begin == '0000-00-00 00:00:00')
                                            <span>{{ priceFormat($product->price_ttc) }}</span>
                                            @if(!empty($product->reduce_price))
                                                {{ priceFormat($product->price_ttc - $product->reduce_price) }}
                                            @endif
                                            @if(!empty($product->reduce_percent))
                                                {{ priceFormat(
                                                    $product->price_ttc -
                                                    ($product->price_ttc * (($product->reduce_percent/100)))
                                                ) }}
                                            @endif
                                        @elseif($product->reduce_date_begin <= date('Y-m-d H:i:s') && $product->reduce_date_end > date('Y-m-d H:i:s'))
                                            <span>{{ priceFormat($product->price_ttc) }}</span>
                                            @if(!empty($product->reduce_price))
                                                {{ priceFormat($product->price_ttc - $product->reduce_price) }}
                                            @endif
                                            @if(!empty($product->reduce_percent))
                                                {{ priceFormat(
                                                    $product->price_ttc -
                                                    ($product->price_ttc * (($product->reduce_percent/100)))
                                                ) }}
                                            @endif
                                        @else
                                            {{ priceFormat($product->price_ttc) }}
                                        @endif
                                    @else
                                        {{ priceFormat($product->price_ttc) }}
                                    @endif

                                </div>

                            </div>

                            @if($product->stock_available > 0)
                                <form method="post"
                                      action="{{ route('cart.product.add') }}"
                                      class="form-product-to-basktet"
                                >
                                    {{ csrf_field() }}
                                    <input type="hidden" name="product_id" value="{{ $product->uuid }}">

                                    @foreach($declinaisons as $key => $attributes)
                                        <div class="form-item">
                                            {{ Form::label($key, $key) }}
                                            <select name="declinaisons[{{ $key }}]">
                                                @foreach($attributes as $attribute)
                                                   <option value="{{ $attribute }}">{{ $attribute }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach

                                    <div class="form-item">
                                        {{ Form::label('quantity', trans('shop.quantity') ) }}
                                        {{ Form::number('quantity', 1, ['min' => 1, 'max' => $product->stock_available]) }}
                                    </div>

                                    <button type="submit">
                                        <i class="fas fa-shopping-cart"></i>
                                        {{ trans('shop.addBasket') }}
                                    </button>
                                </form>
                            @else
                                <div class="no-product">
                                    Produit indisponible
                                </div>
                            @endif

                            @if(!empty(setting('products.socialEnable')))
                                {!! $share !!}
                            @endif

                        </div>

                    </div>
                </div>

                <nav class="tabs" data-component="tabs">
                    <ul>
                        <li class="active"><a href="#description">Descriptions</a></li>
                        @if(setting('products.commentEnable'))
                            <li><a href="#comments">Commentaires ({{ count($comments) }})</a></li>
                        @endif
                    </ul>
                </nav>

                <div class="tab-content" id="description">
                    {!! $product->getTranslation('description', config('app.locale')) !!}
                </div>
                @if(setting('products.commentEnable'))
                    <div class="tab-content" id="comments">

                        <div class="comments">
                            @foreach($comments as $comment)
                                <div class="comment">
                                    <div class="user-date">
                                        <div class="user">
                                            {{ $comment->firstname }} {{ $comment->lastname }}
                                        </div>
                                        <div class="date">
                                            {{ $comment->created_at->format('d/m/Y à H:i') }}
                                        </div>
                                    </div>
                                    <p>
                                        {{ $comment->comment }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        @if(auth()->check())
                            <form method="post"
                                  action="{{ route('product.comment',['uuid' => $product->uuid]) }}"
                            >
                                {{ csrf_field() }}
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
                                <div class="form-item">
                                    {{ Form::label('comment', 'Commentaire' ) }}
                                    {{ Form::textarea('comment', null, ['required' => 'required']) }}
                                </div>
                                <button type="submit">
                                    Envoyer
                                </button>
                            </form>
                        @else
                            <div class="connect-user">
                                <p>
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Pour laisser un commentaire, veuillez vous connecter à votre compte client.
                                </p>
                            </div>
                        @endif

                    </div>
                @endif
            </div>

        </div>
    </div>
    <div class="l-container">
        <div class="related-products">
            <div class="title">
                <div>
                    <h4>Vous aimerez aussi...</h4>
                </div>
                @if(count($relatedProducts) > 6)
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
            <div class="slider-related-products">
                @if(isset($relatedProducts) && !empty($relatedProducts))
                    @foreach($relatedProducts as $product)
                        @cache("product::cache.product",compact('cache.product'),null, $product->id."_".config('app.locale'))
                    @endforeach
                @else
                    @foreach($productAssociates as $productAssociate)
                        @php $product = $productAssociate->Product @endphp
                        @cache("product::cache.product",compact('cache.product'),null, $product->id."_".config('app.locale'))
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @if($contents)
        <div class="home-contents">
            <div class="l-container">
                <div class="row gutters auto align-center">
                    @foreach($contents as $content)
                        <div class="col">
                            @cache('content::cache.home',compact('content'),null, $content->id."_".config('app.locale'))
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="remodal remodal-product" data-remodal-id="product-image-modal"
         data-remodal-options="hashTracking: false">

        <div data-remodal-action="close" class="remodal-close"></div>
        <div class="product-img center-text text-center"></div>
    </div>

@endsection