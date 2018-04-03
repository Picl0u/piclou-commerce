@extends('layouts.app')

@section('content')
<div class="head-title">
    <div class="l-container">
        <h1>Mot de passe oublié ?</h1>
    </div>
</div>
<div class="login-register-section">

    <div class="l-container">
        <div class="login-section">

            {!! Form::open(['route' => 'password.email']) !!}

            <div class="form-item">
                {{ Form::label('email', __('form.email')) }}
                {{ Form::email('email', null) }}
            </div>

            <button type="submit">
                {{ __('form.continue') }}
            </button>


            {!! Form::close() !!}

        </div>
        <hr>
        <div class="links">
            <a href="{{ route('login') }}">
                <i class="fas fa-chevron-left"></i>
                {{ __('generals.return') }}
            </a>
        </div>
    </div>
</div>
@endsection
