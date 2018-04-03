@extends('layouts.app')

@section('seoTitle')
    {{ __('contact::front.title') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('contact::front.title') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')
    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('contact::front.title') }}</h1>
        </div>
    </div>

    @include('includes.searchBar',compact('arianne'))

    <div class="l-container contact-section">

        {!! Form::open(['route' => ["contact.send"]]) !!}

            @if(count($errors) > 0)
                <div class="message error" data-component="message">
                    <h5 style="color:#fff">{{ __('contact::front.warning') }}</h5>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <span class="close small"></span>
                </div>
            @endif

            <div class="form-item">
                {{ Form::label('firstname', __('contact::front.firstname')) }}
                {{ Form::text('firstname', null) }}
            </div>

            <div class="form-item">
                {{ Form::label('lastname', __('contact::front.lastname')) }}
                {{ Form::text('lastname', null) }}
            </div>

            <div class="form-item">
                {{ Form::label('email', __('contact::front.email')) }}
                {{ Form::email('email', null) }}
            </div>

            <div class="form-item">
                {{ Form::label('message', __("contact::front.message")) }}
                {{ Form::textarea('message', null) }}
            </div>

            <button type="submit">
               {{ __("contact::front.send") }}
            </button>

        {!! Form::close() !!}
    </div>

    @if($contents)
        <div class="home-contents">
            <div class="l-container">
                <div class="row gutters auto align-center">
                    @foreach($contents as $content)
                        <div class="col">
                            @cache('content::cache.home',compact('content'), null, $content->id."_".config('app.locale'))
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

@endsection