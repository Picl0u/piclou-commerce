<div class="product">
    @php
        $productLink = route('product.show',[
            'slug' => $product->getTranslation('slug', config('app.locale')),
            'id' => $product->id
        ]);
    @endphp
    <div class="row gutters">
        <div class="col col-6 product-image">
            @if(!empty($product->image))
                <a href="{{ $productLink }}">
                    <img
                        src="{{ resizeImage($product->getMedias('image','src'), null, 280) }}"
                        alt="{{ $product->getMedias('image','alt') }}"
                    >
                </a>
            @endif
        </div>
        <div class="col col-6 product-infos">
            <div class="countdown"
                 data-date="{{ formatDate($product->reduce_date_end,'Y-m-d H:i:s')}}"
            >
                <div class="count days">
                    <p>{{ trans('shop.days') }}</p>
                    <span>8</span>
                </div>
                <div class="count hours">
                    <p>{{ trans('shop.hours') }}</p>
                    <span>13</span>
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
            <h3>
                <a href="{{ $productLink }}">
                    {{ $product->getTranslation('name', config('app.locale')) }}
                </a>
            </h3>
            <div class="product-summary">
                {!! $product->getTranslation('summary', config('app.locale')) !!}
            </div>
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
                {{ trans('shop.stillAvailable') }} : {{ $product->stock_available }}
            </div>
            <div class="product-action">
                <a href="{{ $productLink }}">
                    <i class="fas fa-long-arrow-alt-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>