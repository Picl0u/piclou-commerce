@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Personnaliser</span></li>
            <li><span>Newsletter</span></li>
            <li><span>Contenus</span></li>
            <li><span>Ajoute</span></li>
        </ul>
    </nav>

    <h2>
        Ajouter un contenu
        <span> - Ajouter une nouveau contenu pour votre newsletter</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.newsletter.content.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "admin.newsletter.content.store", 'files' => true]) !!}
        @include('newsletter::admin.content.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}
@endsection
