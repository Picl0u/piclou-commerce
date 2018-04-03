@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Clients</span></li>
            <li><span>Adresses</span></li>
            <li><span>Ajouter une adresse</span></li>
        </ul>
    </nav>

    <h2>
        Ajouter une adresses
        <span> - Ajouter une nouvelle adresses pour vos clients</span>
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

    {!! Form::open(['route' => "admin.addresses.store"]) !!}
        @include('user::admin.addresses.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}
@endsection
