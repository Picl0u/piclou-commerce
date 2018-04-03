@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Personnaliser</span></li>
            <li><span>Newsletter</span></li>
            <li><span>Ajouter</span></li>
        </ul>
    </nav>

    <h2>
        Ajouter une inscription
        <span> - Ajouter une nouvelle inscription pour votre newsletter</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.newsletter.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "admin.newsletter.store"]) !!}
        @include('newsletter::admin.newsletter.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}
@endsection
