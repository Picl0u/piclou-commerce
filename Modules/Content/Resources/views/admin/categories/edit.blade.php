@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>{{ __("admin::navigation.personalize") }}</span></li>
            <li><span>{{ __("content::admin.navigation_pages") }}</span></li>
            <li><span>{{ __("content::admin.navigation_contents") }}</span></li>
            <li><span>{{ __("admin::actions.edit") }} : {{ $data->name }}</span></li>
        </ul>
    </nav>

    <h2>
        {{ __("admin::actions.edit") }} : {{ $data->name }}
        <span> - {{ __("content::admin.edit_title_category") }}</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.pages.categories.index") }}">
            <i class="fas fa-arrow-left"></i>
            {{ __("admin::actions.return") }}
        </a>
        <div class="clear"></div>
    </div>

    <div class="translate-actions">
        {!! formTranslate(\Modules\Content\Entities\ContentCategory::class, $data)
        ->action('admin.pages.categories.translate')
        ->render() !!}
    </div>

    {!! Form::open(['route' => ["admin.pages.categories.update", $data->uuid], 'files' => true]) !!}
    @include('content::admin.categories.form',compact('data'))
    <div class="form-buttons">
        {{ Form::submit( __("admin::actions.edit") ) }}
    </div>
    {!! Form::close() !!}
@endsection
