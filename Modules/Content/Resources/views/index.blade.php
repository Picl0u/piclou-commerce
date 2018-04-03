@extends('layouts.app')

@section('seoTitle')
    @if(!empty($content->seo_title))
        {{ $content->getTranslation('seo_title', config('app.locale')) }} - {{ Setting('generals.seoTitle') }}
    @else
        {{ $content->getTranslation('name', config('app.locale')) }} - {{ Setting('generals.seoTitle') }}
    @endif
@endsection

@section('seoDescription')
    @if(!empty($content->seo_description))
        {{ $content->getTranslation('seo_description', config('app.locale')) }} - {{ Setting('generals.seoDescription') }}
    @else
        {{ $content->getTranslation('name', config('app.locale')) }} - {{ Setting('generals.seoDescription') }}
    @endif
@endsection

@section('content')
    <div class="head-title">
        <div class="l-container">
            <h1>{{ $content->getTranslation('name', config('app.locale')) }}</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))
    <div class="l-container content-section">
        @if(!is_null($category))
            <div class="row gutters">
                <div class="col col-2 sidebar">
                    <h2>{{ $category->getTranslation('name', config('app.locale')) }}</h2>
                    <nav class="sidebar-navigation">
                        <ul>
                            @foreach($contentList as $c)
                                <li>
                                    <a href="{{ Route(
                                        'content.index',[
                                            'slug' => $c->getTranslation('slug', config('app.locale')),
                                            'id' => $c->id
                                        ])}}">{{ $c->getTranslation('name', config('app.locale')) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
                <div class="col col-10 content">
                    <div class="title">
                        <h3>{{ $content->getTranslation('name', config('app.locale')) }}</h3>
                    </div>
                    {!! $content->getTranslation('description', config('app.locale')) !!}
                    @if((is_null($content->on_homepage) || empty($content->on_homepage)) && !empty($content->image))
                        <div class="content-image">
                            <img src="{{ asset($content->image) }}" alt="{{ $content->name }}">
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="content">
                <div class="title">
                    <h3>{{ $content->getTranslation('name', config('app.locale')) }}</h3>
                </div>
                {!! $content->getTranslation('description', config('app.locale')) !!}

                @if((is_null($content->on_homepage) || empty($content->on_homepage)) && !empty($content->image))
                    <div class="content-image">
                        <img src="{{ asset($content->image) }}" alt="{{ $content->name }}">
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection