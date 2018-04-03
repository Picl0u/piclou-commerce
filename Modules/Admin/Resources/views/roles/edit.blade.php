@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Personnaliser</span></li>
            <li><span>Administrateurs</span></li>
            <li><span>Rôles</span></li>
            <li><span>Modifier : {{ $data->name }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier le rôle : {{ $data->name }}
        <span> - Modifier ce rôle pour vos administrateurs</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.roles.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => ["admin.roles.update", $data->uuid]]) !!}
        @include('admin::roles.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Editer') }}
        </div>
    {!! Form::close() !!}
@endsection
