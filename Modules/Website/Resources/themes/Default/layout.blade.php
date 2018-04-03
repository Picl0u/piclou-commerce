<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO -->
    <title>@yield('seoTitle')</title>
    <meta name="description" content="@yield('seoDescription')">
    <meta name="keywords" content="">
    <meta name="robots" content="noindex;nofollow;">
    <meta http-equiv="content-language" content="fr">
    <meta name="language" content="fr">
    <meta name="resource-type" content="text">
    <meta property="og:title" content="@yield('seoTitle')">
    <meta property="og:description" content="@yield('seoDescription')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ URL::full() }}">
    <meta property="og:site_name" content="{{ Setting('generals.websiteName') }}">
    <meta property="og:image" content="@yield('seoImage')">
    <meta name="p:domain_verify" content="2fdc0ea41a0e1c12ec6999707d581ce7">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @if(!empty(setting('generals.analytics')))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('generals.analytics') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', {{ setting('generals.analytics') }});
        </script>
    @endif

</head>
<body>
    <div id="app">

        <header>
            <div class="header-top">
                <div class="l-container">
                    <div class="row gutters align-middle">
                        <div class="col col-6 text-left">
                            @if(!empty(setting('generals.facebook')))
                                <a href="{{ setting('generals.facebook') }}" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if(!empty(setting('generals.twitter')))
                                <a href="{{ setting('generals.twitter') }}" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if(!empty(setting('generals.pinterest')))
                                <a href="{{ setting('generals.pinterest') }}" target="_blank">
                                    <i class="fab fa-pinterest-p"></i>
                                </a>
                            @endif
                            @if(!empty(setting('generals.googlePlus')))
                                <a href="{{ setting('generals.googlePlus') }}" target="_blank">
                                    <i class="fab fa-google-plus-g"></i>
                                </a>
                            @endif
                            @if(!empty(setting('generals.instagram')))
                                <a href="{{ setting('generals.instagram') }}" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                            @if(!empty(setting('generals.youtube')))
                                <a href="{{ setting('generals.youtube') }}" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif
                            @php $langs = array_diff(config('ikCommerce.languages'),[config('app.locale')]); @endphp
                            @foreach($langs as $lang)
                                <a href="{{ route('change.language',['locale' => $lang]) }}">
                                    {{ strtoupper($lang) }}
                                </a>
                            @endforeach
                        </div>
                        <nav class="col col-6 text-right ecommerce-navigation">
                            <ul>
                                <li>
                                    @if(Auth::user())
                                        @if(Auth::user()->role == 'user')
                                            <a href="{{ route('user.account') }}"
                                               data-component="dropdown"
                                               data-target="#dropdown-user"
                                            >
                                                <i class="fas fa-user"></i>
                                                {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                                                <span class="caret down"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}">
                                                <i class="fas fa-user"></i>
                                                {{ __('user.login') }}
                                            </a>
                                            <a href="{{ route('register') }}">
                                                <i class="fas fa-user-plus"></i>
                                                {{ __('user.register') }}
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}">
                                            <i class="fas fa-user"></i>
                                            {{ __('user.login') }}
                                        </a>
                                        <a href="{{ route('register') }}">
                                            <i class="fas fa-user-plus"></i>
                                            {{ __('user.register') }}
                                        </a>
                                    @endif
                                </li>
                                <li>
                                    <a href="{{ route('whishlist.index') }}">
                                        <i class="far fa-heart"></i>
                                        {{ __('shop.whishlist') }}
                                       (<span class="whishlist-count">{{ Cart::instance('whishlist')->count() }}</span>)
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="header-middle">
                <div class="l-container">
                    <div class="row gutters align-center align-middle">

                        <div class="col col-4">
                            {!! Form::open(['route' => 'product.search', 'method' => 'get']) !!}
                                <input type="text"
                                       name="keywords"
                                       placeholder="{{ __('shop.searchProduct') }}"
                                       value=""
                                       required="required"
                                >
                                <button type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <div class="clear"></div>
                            {!! Form::close() !!}
                        </div>

                        <div class="col col-4">
                            <a href="/" class="logo">
                                <img src="{{ asset(setting('generals.logo')) }}" alt="{{ setting('generals.websiteName') }}" >
                            </a>
                        </div>

                        <div class="col col-4 text-right header-shopping">
                            <a href="{{ route('cart.show') }}">
                                <span class="shopping-count">
                                    {{ Cart::instance('shopping')->count() }}
                                    {{ (Cart::instance('shopping')->count()>1)?__('shop.products'):__('shop.product') }}
                                </span>
                                <span class="shopping-total">
                                     - {{ Cart::instance('shopping')->total() }}&euro;
                                </span>
                                <span class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="header-bottom">
                <div class="l-container">
                    <div class="responsive-menu"
                         data-component="offcanvas"
                         data-target="#offcanvas-right"
                         data-direction="right"
                    >
                        <i class="fas fa-bars"></i>
                    </div>
                    <nav class="main-navigation">
                        @include('includes.mainMenu')
                    </nav>
                </div>
            </div>
        </header>
        @if(Auth::user())
            @if(Auth::user()->role == 'user')
                <div class="dropdown hide" id="dropdown-user">
                    <ul>
                        <li>
                            <a href="{{ route('user.account') }}">
                                {{ __('user::user.my_account') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.infos') }}">
                                {{ __('user::user.my_informations') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.addresses') }}">
                                {{ __('user::user.my_addresses') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('order.index') }}">
                                {{ __('user::user.my_orders') }}
                            </a>
                        </li>
                        <li class="user-logout">
                            <a href="{{ route('logout') }}">
                                {{ __('user::user.logout') }}
                            </a>
                            <form class="logout-form" action="{{ route('logout') }}" method="POST">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            @endif
        @endif

        @yield('content')

        <div class="remodal modal-cart" data-remodal-id="modal-cart">
            <div data-remodal-action="close" class="remodal-close"></div>
            <div class="modal-title">
                <i class="fas fa-check"></i>
                Produit ajouté au panier avec succès
            </div>
            <div class="row gutters">
                <div class="col col-5 cart-product">
                    <div class="row gutters">
                        <div class="col col-6 cart-product-image">
                        </div>
                        <div class="col col-6 cart-product-infos">
                            <div class="product-name"></div>
                            <div class="product-price"></div>
                            <div class="product-quantity">{{ trans('shop.quantity') }} :<span></span></div>
                        </div>
                    </div>
                </div>
                <div class="col col-7 cart-infos">
                    <p class="total-count"></p>
                    <p class="transport"></p>
                    <p class="total"></p>
                    <div class="link">
                        <a href="#" data-remodal-action="close">Continuer mes achats</a>
                        <a href="{{ route('cart.show') }}"><i class="fas fa-check"></i> Commander</a>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="l-container">
                <nav class="footer-nav row gutters">
                    {!! footerNavigation() !!}
                </nav>
            </div>
        </footer>

        <div id="offcanvas-right" class="hide">
            <a href="#" class="close"></a>
            <nav class="responsive-nav"></nav>
        </div>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @if(Session::has('success'))
        <script>
            $.toast({
                heading: 'Merci',
                text:  '{{ Session::get('success') }}',
                showHideTransition: 'slide',
                icon: 'success',
                position:'top-left'
            });
        </script>
    @endif
    @if(Session::has('error'))
        <script>
            $.toast({
                heading: 'Erreur !',
                text:  '{{ Session::get('error') }}',
                showHideTransition: 'slide',
                icon: 'error',
                position:'top-left'
            });
        </script>
    @endif
</body>