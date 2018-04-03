<div class="slide">
    <img
        src="/{{ str_replace('\\', '/',$slide->image) }}"
        alt="{{ $slide->name }}"
        class="img-to-background"
    >
    <div class="slide-description {{ $slide->position }}">
        <div class="content">
            {!! $slide->description !!}
        </div>
    </div>
    @if(!empty($slide->link))
        <a href="{{ $slide->link }}"></a>
    @endif
</div>