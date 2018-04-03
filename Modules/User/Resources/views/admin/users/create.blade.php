@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Clients</span></li>
            <li><span>Clients</span></li>
            <li><span>Ajouter un utilisateur</span></li>
        </ul>
    </nav>

    <h2>
        Ajouter un utilisateur
        <span> - Ajouter un nouvel utilisateur pour votre site Internet</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.users.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "admin.users.store"]) !!}
        @include('user::admin.users.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}
@endsection
