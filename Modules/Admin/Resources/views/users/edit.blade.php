@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Configurer</span></li>
            <li><span>Administrateurs</span></li>
            <li><span>Modifier : {{ $data->firstname }} {{ $data->lastname }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier l'administrateur : {{ $data->firstname }} {{ $data->lastname }}
        <span> - Modifier cet administrateur pour votre site Internet</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.admin.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => ["admin.admin.update", $data->uuid]]) !!}
        @include('admin::users.form',compact('data','roles'))
        <div class="form-buttons">
            {{ Form::submit('Editer') }}
        </div>
    {!! Form::close() !!}
@endsection
