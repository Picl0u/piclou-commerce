Traduire :
@foreach ($langs as $lang)
    <a href="#translate-modal" data-lang="{{ $lang }}">
        <i class="fas fa-plus"></i>
        {{ $lang }}
    </a>
@endforeach