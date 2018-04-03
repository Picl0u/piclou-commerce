@if($content->image)
    <div class="content-image">
        <img
            src="{{ asset($content->image) }}"
            alt="{{ $content->getTranslation('name', config('app.locale')) }}"
        >
    </div>
@endif
<h5>{{ $content->getTranslation('name', config('app.locale'))}}</h5>
{!! $content->getTranslation('summary', config('app.locale')) !!}
<a href="
    {{ route('content.index',[
        'slug' => $content->getTranslation('slug', config('app.locale')),
        'id' => $content->id
    ]) }}"
   class="read-more">
    {{ __('generals.readMore') }}
</a>