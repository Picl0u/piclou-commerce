@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>{{ __("admin::navigation.personalize") }}</span></li>
            <li><span>{{ __("content::admin.navigation_pages") }}</span></li>
            <li><span>{{ __("content::admin.navigation_contents") }}</span></li>
            <li><span>{{ __("admin::actions.add") }}</span></li>
        </ul>
    </nav>

    <h2>
        {{ __("admin::actions.add") }}
        <span> - {{ __("content::admin.create_title_category") }}</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.pages.contents.index") }}">
            <i class="fas fa-arrow-left"></i>
            {{ __("admin::actions.return") }}
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "admin.pages.contents.store", 'files' => true]) !!}
        @include('content::admin.contents.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit(__("admin::actions.save")) }}
        </div>
    {!! Form::close() !!}
@endsection
