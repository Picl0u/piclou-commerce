@extends('layouts.app')

@section('seoTitle')
    {{ __('user::user.my_addresses') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('user::user.my_addresses') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')

    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('user::user.my_addresses') }}</h1>
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
                        <h2> {{ __('user::user.my_addresses') }}</h2>
                        <a href="{{ route('user.addresses.create') }}">
                            <i class="fas fa-plus"></i>
                            {{ __('user::user.add') }}
                        </a>
                    </div>

                    <div class="addresses">
                        <div class="row gutters">
                            @foreach($addresses as $address)
                                <div class="col col-4 address">
                                    <strong>{{ $address->firstname }} {{ $address->lastname }}</strong><br>
                                    {{ $address->address }} {{ $address->additional_address }}<br>
                                    {{ $address->zip_code }} {{ $address->city }}<br>
                                    {{ $address->Country->name }}<br>
                                    {{ __('user::user.phone') }} : {{ $address->phone }}
                                    <div class="link">

                                        <a href="{{ route('user.addresses.edit',['uuid' => $address->uuid]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                            {{ __('user::user.edit') }}
                                        </a>

                                        <a href="{{ route('user.addresses.delete',['uuid' => $address->uuid]) }}"
                                           class="delete-confirm"
                                        >
                                            <i class="fas fa-trash"></i>
                                            {{ __('user::user.delete') }}
                                            <span class="delete-infos"
                                                  data-title="{{ __('user::user.warning') }}"
                                                  data-message="{{ __('user::user.delete_message') }}"
                                                  data-confirm="{{ __('user::user.confirm') }}"
                                                  data-cancel="{{ __('user::user.cancel') }}"
                                            ></span>
                                        </a>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
