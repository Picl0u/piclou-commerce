@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Commandes</span></li>
            <li><span>Statuts de commande</span></li>
            <li><span>Ajouter</span></li>
        </ul>
    </nav>

    <h2>
        Ajouter un statut
        <span> - Ajouter un nouveau statut pour vos commandes</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("orders.status.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "orders.status.store"]) !!}
        @include('order::admin.status.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}
@endsection
