@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Personnaliser</span></li>
            <li><span>Newsletter</span></li>
            <li><span>Modifier : {{ $data->email }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier l'inscription : {{ $data->email }}
        <span> - Modifier cette inscription pour votre newsletter</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.newsletter.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => ["admin.newsletter.update", $data->uuid]]) !!}
        @include('newsletter::admin.newsletter.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Editer') }}
        </div>
    {!! Form::close() !!}
@endsection
