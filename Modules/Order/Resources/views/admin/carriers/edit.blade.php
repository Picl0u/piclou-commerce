@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Commandes</span></li>
            <li><span>Transporteurs</span></li>
            <li><span>Editer : {{ $data->name }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier le transporteur : {{ $data->name }}
        <span> - Modifier ce transporteur pour vos commandes</span>
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

    {!! Form::open(['route' => ["orders.carriers.update", $data->uuid], 'files' => true]) !!}
    @include('order::admin.carriers.form',compact('data'))
    <div class="form-buttons">
        {{ Form::submit('Editer') }}
    </div>
    {!! Form::close() !!}
@endsection
