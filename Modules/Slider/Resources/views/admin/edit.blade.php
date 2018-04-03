@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Slider</span></li>
            <li><span>Modifier : {{ $data->name }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier la slide : {{ $data->name }}
        <span> - Modifier cette slide pour votre slider</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("sliders.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    <div class="translate-actions">
        {!! formTranslate(\Modules\Slider\Entities\Slider::class, $data)
        ->action('admin.sliders.translate')
        ->render() !!}
    </div>

    {!! Form::open(['route' => ["sliders.update", $data->uuid], 'files' => true]) !!}
    @include('slider::admin.form',compact('data'))
    <div class="form-buttons">
        {{ Form::submit('Editer') }}
    </div>
    {!! Form::close() !!}
@endsection
