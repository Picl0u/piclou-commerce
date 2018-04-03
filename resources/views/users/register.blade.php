{!! Form::open(['route' => 'register']) !!}

    <label>{{ trans('form.civility') }}</label>
    <div class="form-item form-checkboxes">
        <label class="checkbox">
            <input type="radio" name="gender" value="M" checked="checked">
            M.
        </label>
        <label class="checkbox">
            <input type="radio" name="gender" value="Mme">
            Mme
        </label>
    </div>

    <div class="form-item">
        {{ Form::label('firstname', trans('form.firstname')) }}
        {{ Form::text('firstname', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('lastname', trans('form.lastname')) }}
        {{ Form::text('lastname', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('email', trans('form.email')) }}
        {{ Form::email('email', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('password', trans('form.password')) }}
        {{ Form::password('password', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('password_confirmation', trans('form.password_confirm')) }}
        {{ Form::password('password_confirmation', null) }}
    </div>

    <div class="form-item">
        <label class="checkbox">
            <input type="checkbox" name="newsletter" checked="checked">
            Recevoir notre newsletter
        </label>
        <div class="desc">
            Vous pouvez vous désinscrire à tout moment.
        </div>
    </div>

    <button type="submit">
        {{ trans('form.register') }}
    </button>

{!! Form::close() !!}