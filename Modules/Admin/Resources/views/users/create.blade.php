@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Configurer</span></li>
            <li><span>Administrateurs</span></li>
            <li><span>Ajouter un administrateur</span></li>
        </ul>
    </nav>

    <h2>
        Ajouter un administrateur
        <span> - Ajouter un nouvel administrateur pour votre site Internet</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.admin.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "admin.admin.store"]) !!}
        @include('admin::users.form',compact('data','roles'))
        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}
@endsection
