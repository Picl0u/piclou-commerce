@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Catégories</span></li>
            <li><span>Ajouter</span></li>
        </ul>
    </nav>

    <h2>
        Ajouter une catégorie
        <span> - Ajouter une nouvelle catégorie pour votre boutique</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("shop.categories.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "shop.categories.store", 'files' => true]) !!}
        @include('product::admin.categories.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}
@endsection
