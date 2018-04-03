@extends("layouts.admin")

@section('title')
    <h2>
        Dashboard
        <span> - Bienvenue dans votre espace administration</span>
    </h2>
@endsection

@section('content')
    {!! $widgets !!}
@endsection