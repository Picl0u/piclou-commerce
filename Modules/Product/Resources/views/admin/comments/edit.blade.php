@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Commentaires</span></li>
            <li><span>Modifier : {{ $data->Product->name }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier le commentaire pour le produit : { $data->Product->name }}
        <span> - Modifier le commentaire pour votre produit</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.products.comments.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => ["admin.products.comments.update", $data->uuid]]) !!}
    @include('product::admin.comments.form',compact('data','users','products'))
    <div class="form-buttons">
        {{ Form::submit('Editer') }}
    </div>
    {!! Form::close() !!}
@endsection
