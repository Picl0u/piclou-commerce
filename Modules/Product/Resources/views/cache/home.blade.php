<div class="col col-4 category">
    @if(!empty($category->image))
        <img src="{{ str_replace('\\', '/',$category->image) }}"
             alt="{{ $category->getTranslation('name', config('app.locale')) }}"
             class="img-to-background"
        >
    @endif
    <div class="mask"></div>
    <div class="content">
        <h2>{{ $category->getTranslation('name', config('app.locale'))}}</h2>
        <div class="arrow">
            <i class="fas fa-arrow-right"></i>
        </div>
    </div>
    <a href="{{ route('product.list',[
        'slug' => $category->getTranslation('slug', config('app.locale')),
        'id' => $category->id
        ]) }}"></a>
</div>