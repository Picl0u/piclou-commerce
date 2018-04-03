@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Clients</span></li>
            <li><span>Clients</span></li>
            <li><span>Modifier : {{ $data->firstname }} {{ $data->lastname }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier le client : {{ $data->firstname }} {{ $data->lastname }}
        <span> - Modifier ce client pour votre site Internet</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.users.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => ["admin.users.update", $data->uuid]]) !!}
    @include('user::admin.users.form',compact('data'))
    <div class="form-buttons">
        {{ Form::submit('Editer') }}
    </div>
    {!! Form::close() !!}
@endsection
