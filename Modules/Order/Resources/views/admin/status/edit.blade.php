@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Commandes</span></li>
            <li><span>Statuts de commande</span></li>
            <li><span>Modifier : {{ $data->name }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier le statut : {{ $data->name }}
        <span> - Modifier ce statut pour vos commandes</span>
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

    <div class="translate-actions">
        {!! formTranslate(\Modules\Order\Entities\Status::class, $data)
        ->action('admin.orders.status.translate')
        ->render() !!}
    </div>

    {!! Form::open(['route' => ["orders.status.update", $data->uuid]]) !!}
    @include('order::admin.status.form',compact('data'))
    <div class="form-buttons">
        {{ Form::submit('Editer') }}
    </div>
    {!! Form::close() !!}
@endsection
