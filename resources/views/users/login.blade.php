{!! Form::open(['route' => 'login']) !!}

    <div class="form-item">
        {{ Form::label('email', __('form.email')) }}
        {{ Form::email('email', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('password', __('form.password')." - optionnel") }}
        {{ Form::password('password', null) }}
    </div>

    <div class="form-item">
        <label class="checkbox">
            <input type="checkbox" name="remember" checked="checked"> Se souvenir de moi ?
        </label>
    </div>

    <button type="submit">
        {{ __('form.login') }}
    </button>
    <div class="forgot-password">
        <a href="{{ route('password.request') }}">
            Mot de passe oublié ?
        </a>
    </div>

{!! Form::close() !!}