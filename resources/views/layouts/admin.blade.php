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
        @yield('style')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link href="{{ asset('libs/remodal/remodal.css') }}" rel="stylesheet">
        <link href="{{ asset('libs/remodal/remodal-default-theme.css') }}" rel="stylesheet">
        {!! Charts::styles() !!}
        <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
        <!-- Script Charts -->
        {!! Charts::scripts() !!}
    </head>
    <body>

        <header class="cd-main-header">
            <a href="{{ route('admin.dashboard') }}" class="cd-logo">
                <img src="{{ asset(setting('generals.logo')) }}" alt="Logo">
            </a>

            <div class="cd-search is-hidden">
                <h1>{{ setting('generals.websiteName') }}</h1>
            </div> <!-- cd-search -->

            <a href="#0" class="cd-nav-trigger">
                <i class="fas fa-bars"></i>
            </a>

            <nav class="cd-nav">
                <ul class="cd-top-nav">
                    <li><a href="{{ route("orders.orders.index") }}">Commandes</a></li>
                    <li><a href="{{ route('shop.products.create') }}">Ajouter un produit</a></li>
                    <li><a href="/" target="_blank">Voir le site</a></li>
                    @php $langs = array_diff(config('ikCommerce.languages'),[config('app.locale')]); @endphp
                    @foreach($langs as $lang)
                        <li>
                            <a href="{{ route('change.language',['locale' => $lang]) }}">
                                {{ strtoupper($lang) }}
                            </a>
                        </li>
                    @endforeach
                    <li class="has-children account">
                        <a href="#0">
                            <img src="{{ asset('images/admin/cd-avatar.png') }}" alt="avatar">
                            {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                        </a>

                        <ul>
                            <!--<li><a href="#0">Mon compte</a></li>
                            <li><a href="#0">Editer</a></li>-->
                            <li class="user-logout">
                                <a href="{{ route('admin.logout') }}">
                                    {{ __('user::user.logout') }}
                                </a>
                                <form class="logout-form" action="{{ route('admin.logout') }}" method="POST">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>

        <div class="cd-main-content">
            <nav class="cd-side-nav">
                @include("admin.menu")
                <div class="navigation-footer">
                    <p>
                        Développé avec <i class="fas fa-heart"></i> par
                        <a href="{{ config('ikCommerce.website') }}" target="_blank">{{ config('ikCommerce.author') }}</a>
                        - v{{ config('ikCommerce.version') }}
                    </p>
                </div>
            </nav>
            <div id="mediasApp"></div>
            <div class="content-wrapper" id="admin-app">

                <div class="content-title">
                    @yield('title')
                </div>
                <div class="content">

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

                    @yield('content')
                </div>

            </div>
            <div class="clear"></div>
        </div>

        <div class="remodal" data-remodal-id="remodal" data-remodal-options="hashTracking:false">
            <span data-remodal-action="close" class="remodal-close"></span>
            <div class="forImg">

            </div>
        </div>

        <div class="remodal" data-remodal-id="medias-update" data-remodal-options="hashTracking:false">
            <span data-remodal-action="close" class="remodal-close"></span>
            <div class="update-medias">
                <h5>Editer le média : <span class="medias-name"></span></h5>
                <form method="post" action="">
                    {{ csrf_field() }}
                    <div class="form-item">
                        {{ Form::label('alt','Balise alt') }}
                        {{ Form::text('alt',null) }}
                    </div>
                    <div class="modal-actions">
                        <button data-remodal-action="cancel" class="remodal-cancel">Annuler</button>
                        <button data-remodal-action="confirm" class="remodal-confirm">{{ __('admin::actions.edit') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Scripts -->
        <script src="{{ asset('js/admin.js') }}"></script>

        @stack('scripts')

            <script type="text/javascript">
                @if(Session::has('success'))
                    $.toast({
                        heading: 'Merci',
                        text:  '{{ Session::get('success') }}',
                        showHideTransition: 'slide',
                        icon: 'success',
                        position:'top-left'
                    });
                @endif
                @if(Session::has('error'))
                    $.toast({
                        heading: 'Erreur !',
                        text:  '{{ Session::get('error') }}',
                        showHideTransition: 'slide',
                        icon: 'error',
                        position:'top-left'
                    });
                @endif
            </script>
    </body>
</html>
