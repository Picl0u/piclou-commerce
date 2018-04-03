<div class="search-bar">
    <div class="l-container">
        @if(isset($sliders) && !empty($sliders) && !empty(setting('slider.dots')))
            <div class="slider-pagination">
                @foreach($sliders as $key => $slide)
                    <span data-slide="{{ $key }}"></span>
                @endforeach
            </div>
        @endif
            @if(isset($arianne) && !empty($arianne))
            <nav class="breadcrumbs">
                <ul>
                    @foreach($arianne as $key => $link)
                        <li><a href="{{ $link}}">{{ $key }}</a></li>
                    @endforeach
                </ul>
            </nav>
        @endif

    </div>
</div>