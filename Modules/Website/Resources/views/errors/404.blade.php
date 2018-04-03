@extends('layouts.app')

@section('seoTitle')
    {{ __('website::front.404_title') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('website::front.404_title') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')
    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('website::front.404_title') }}</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))

    <div class="l-container content-section">
        <div class="content">
            <div class="title">
                <h2>{{ __('website::front.404_title') }}</h2>
            </div>
            <p>
                {{ __('website::front.404_desc') }}
            </p>
        </div>
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