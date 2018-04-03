@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Taxes</span></li>
            <li><span>Modifier : {{ $data->name }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier la taxe : {{ $data->name }}
        <span> - Modifier la taxe pour votre boutique</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("shop.vats.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => ["shop.vats.update", $data->uuid], 'files' => true]) !!}
    @include('vat::admin.form',compact('data'))
    <div class="form-buttons">
        {{ Form::submit('Editer') }}
    </div>
    {!! Form::close() !!}
@endsection
