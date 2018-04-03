@extends('layouts.app')

@section('seoTitle')
    {{ __('user::user.my_informations') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('user::user.my_informations') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')

    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('user::user.my_informations') }}</h1>
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
                        <h2>Mettre à jours mes informations</h2>
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

                    {!! Form::open(['route' => "user.infos.update", 'files' => true]) !!}
                        <label>{{ trans('form.civility') }}</label>
                        <div class="form-item form-checkboxes">
                            <label class="checkbox">
                                <?php
                                    $checked="";
                                    if($user->gender == "M"){
                                        $checked="checked";
                                    }
                                ?>
                                <input type="radio" name="gender" value="M" {{ $checked }}>
                                M.
                            </label>
                            <label class="checkbox">
                                <?php
                                    $checked="";
                                    if($user->gender == "Mme"){
                                        $checked="checked";
                                    }
                                ?>
                                <input type="radio" name="gender" value="Mme" {{ $checked }}>
                                Mme
                            </label>
                        </div>

                        <div class="form-item">
                            {{ Form::label('firstname', trans('form.firstname')) }}
                            {{ Form::text('firstname', $user->firstname) }}
                        </div>

                        <div class="form-item">
                            {{ Form::label('lastname', trans('form.lastname')) }}
                            {{ Form::text('lastname',  $user->lastname) }}
                        </div>

                        <div class="form-item">
                            {{ Form::label('email', trans('form.email')) }}
                            {{ Form::email('email',  $user->email, [ 'disabled'=>'true' ]) }}
                        </div>

                        <div class="form-item">
                            {{ Form::label('password', trans('form.password')) }}
                            {{ Form::password('password', null) }}
                            <div class="desc">Laissez vide si pas de changement</div>
                        </div>

                        <div class="form-item">
                            <label class="checkbox">
                                <?php
                                    $checked="";
                                    if(!empty($user->newsletter)){
                                        $checked="checked";
                                    }
                                ?>
                                <input type="checkbox" name="newsletter" {{ $checked }}>
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
                </div>
            </div>
        </div>

    </div>
@endsection
