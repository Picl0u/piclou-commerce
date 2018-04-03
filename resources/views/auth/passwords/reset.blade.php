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

            {!! Form::open(['route' => 'login']) !!}

                <div class="form-item">
                    {{ Form::label('email', trans('form.email')) }}
                    {{ Form::email('email', null) }}
                </div>

                <div class="form-item">
                    {{ Form::label('password', trans('form.password')." - optionnel") }}
                    {{ Form::password('password', null) }}
                </div>

                <div class="form-item">
                    <label class="checkbox">
                        <input type="checkbox" name="remember" checked="checked"> Se souvenir de moi ?
                    </label>
                </div>

                <button type="submit">
                    {{ trans('form.login') }}
                </button>
                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">
                        Mot de passe oublié ?
                    </a>
                </div>

            {!! Form::close() !!}

        </div>
        <hr>
        <div class="links">
            <p>
                <a href="{{ route('login') }}">{{ __('generals.return') }}</a>
            </p>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
