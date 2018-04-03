<div class="newsletter">
    <img src="{{ str_replace('\\', '/',$newsletter->image) }}"
         alt="{{ $newsletter->getTranslation('name', config('app.locale')) }}"
         class="img-to-background"
    >
    <div class="mask"></div>
    <div class="content">
        {!! $newsletter->getTranslation('description', config('app.locale')) !!}

        {!! Form::open(['route' => ["newsletter.register"]]) !!}
        <div class="form-item">
            {{ Form::label('email', __("newsletter::front.email")) }}
            {{ Form::email('email', null, ['required' => 'required']) }}
        </div>
        <button type="submit">
            {{ __("newsletter::front.register") }}
        </button>
        {!! Form::close() !!}

    </div>

</div>