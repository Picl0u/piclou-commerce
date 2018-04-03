<div class="row gutters">
    <div class="col col-12">
        <div class="widget success">
            <div class="widget-title">
                <span>
                    <i class="fas fa-money-bill-alt"></i>
                </span>
                10 meilleures ventes
            </div>
            <div class="widget-value row gutters align-center small product">
                @foreach($bestSale as $product)
                    <div class="col col-3">
                        <img src="{{ resizeImage($product->getMedias('image','src'),200,200) }}"
                             alt="{{ $product->name }}"
                        >
                        <div class="product-name">
                            <i>{{ $product->ref }}</i> - {{ $product->name }}
                        </div>
                        <div class="product-total">
                            Quantité total commandée : <strong>{{ $product->sum }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>