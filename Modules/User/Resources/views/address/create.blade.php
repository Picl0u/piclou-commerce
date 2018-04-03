@extends('layouts.app')

@section('seoTitle')
    {{ __('user::user.add_address') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('user::user.add_address') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')

    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('user::user.add_address') }}</h1>
        </div>
    </div>
    @include('includes.searchBar',compact('arianne'))

    <div class="account-section">
        <div class="l-container">
            <div class="row gutters">
                <div class="col col-2 sidebar">
                    @include("user::navigation")
                </div>
                <div class="col col-10">

                    <div class="title">
                        <h2> {{ __('user::user.add_address') }}</h2>
                        <a href="{{ route('user.addresses') }}">
                            <i class="fas fa-chevron-left"></i>
                            {{ __('generals.return') }}
                        </a>
                    </div>

                    @if(count($errors) > 0)
                        <div class="message error" data-component="message">
                            <h5 style="color:#fff">Attention !</h5>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <span class="close small"></span>
                        </div>
                    @endif

                    {!! Form::open(['route' => 'user.addresses.store']) !!}
                        @include("user::address.form", compact('data', 'countries'))
                    {!! Form::close() !!}

                </div>
            </div>
        </div>

    </div>
@endsection
