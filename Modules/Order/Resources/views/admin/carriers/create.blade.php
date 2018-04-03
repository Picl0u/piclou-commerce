@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Commandes</span></li>
            <li><span>Transporteurs</span></li>
            <li><span>Ajouter</span></li>
        </ul>
    </nav>

    <h2>
        Ajouter un transporteur
        <span> - Ajouter une nouveau transporteurs pour vos commandes</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("orders.carriers.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "orders.carriers.store", 'files' => true]) !!}
        @include('order::admin.carriers.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}
@endsection
