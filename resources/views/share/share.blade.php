<div class="share">
    <p>{{ __('generals.shareOn') }}</p>
    <ul>
        @foreach($websites as $website)
            <li>
                <a href="{{ $website['url'] }}" target="_blank">
                    {!! $website['name'] !!}
                </a>
            </li>
        @endforeach
    </ul>
</div>