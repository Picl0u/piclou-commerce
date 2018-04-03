<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('generals.websiteName') }} - Administration</title>

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>

    <div class="admin-login">
        <div class="row">
            <div class="col col-6">
                <div class="login-form">
                        {!! Form::open(['route' => 'login']) !!}

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
                            <div class="form-item">
                                {{ Form::label('email', __('form.email')) }}
                                {{ Form::email('email', null) }}
                            </div>

                            <div class="form-item">
                                {{ Form::label('password', __('form.password')) }}
                                {{ Form::password('password', null) }}
                            </div>

                            <div class="form-item">
                                <label class="checkbox">
                                    <input type="checkbox" name="remember" checked="checked"> Se souvenir de moi ?
                                </label>
                            </div>

                            <button type="submit">
                                {{ __('form.login') }}
                            </button>

                        {!! Form::close() !!}
                </div>


            </div>
            <div class="col col-6 login-background">
                <div class="website">
                    <img src="{{ asset(setting('generals.logo')) }}" alt="{{ setting('generals.websiteName') }}" >
                    <h1>{{ setting('generals.websiteName') }}</h1>
                    <p>Bienvenue dans votre espace d'aministration</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/admin.js') }}"></script>
    @if(Session::has('success'))
        <script type="text/javascript">
            $.toast({
                heading: 'Merci',
                text:  '{{ Session::get('success') }}',
                showHideTransition: 'slide',
                icon: 'success',
                position:'bottom-right'
            });
        </script>
    @endif
    @if(Session::has('error'))
        <script type="text/javascript">
            $.toast({
                heading: 'Erreur !',
                text:  '{{ Session::get('error') }}',
                showHideTransition: 'slide',
                icon: 'error',
                position:'bottom-right'
            });
        </script>
    @endif
</body>
</html>
