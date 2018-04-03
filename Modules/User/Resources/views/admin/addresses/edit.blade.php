@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Clients</span></li>
            <li><span>Adresses</span></li>
            <li><span>Modifier l'addresse pour {{ $data->firstname }} {{ $data->lastname }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier l'adresse pour : {{ $data->firstname }} {{ $data->lastname }}
        <span> - Modifier cette adresse pour votre client</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.addresses.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => ["admin.addresses.update", $data->uuid]]) !!}
    @include('user::admin.addresses.form',compact('data'))
    <div class="form-buttons">
        {{ Form::submit('Editer') }}
    </div>
    {!! Form::close() !!}
@endsection
