@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Produits</span></li>
            <li><span>Ajouter</span></li>
        </ul>
    </nav>

    <h2>
        Ajouter un produit
        <span> - Ajouter un nouveau produit pour votre boutique</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("shop.products.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "shop.products.store", 'files' => true]) !!}
        @include('product::admin.products.form',compact('data','products'))
        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}

@endsection
