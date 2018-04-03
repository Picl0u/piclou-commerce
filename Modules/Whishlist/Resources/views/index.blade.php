@extends('layouts.app')

@section('seoTitle')
    {{ __('user::user.my_whishlist') }} - {{ Setting('generals.seoTitle') }}
@endsection

@section('seoDescription')
    {{ __('user::user.my_whishlist') }} - {{ Setting('generals.seoDescription') }}
@endsection

@section('content')

    <div class="head-title">
        <div class="l-container">
            <h1>{{ __('user::user.my_whishlist') }}</h1>
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
                        <h2> {{ __('user::user.my_whishlist') }}</h2>
                    </div>

                    <div class="whishlist">
                        @if(Cart::instance('whishlist')->total() > 0)
                            <div class="row gutters">
                                @foreach(Cart::instance('whishlist')->content() as $row)
                                    <div class="col col-3">
                                        <div class="product">
                                            @if($row->options->image)
                                            <div class="product-image">
                                                <img src="{{ resizeImage($row->options->image,false,190) }}"
                                                     alt="{{ $row->name }}"
                                                >
                                            </div>
                                            @endif

                                            <div class="product-title">
                                                <h4>
                                                    {{ $row->name }}
                                                    <span>
                                                        x{{ $row->qty }}
                                                    </span>
                                                </h4>
                                            </div>

                                            <div class="product-bottom">
                                                <div class="product-prices">
                                                    {{ priceFormat($row->price) }}
                                                </div>
                                            </div>

                                            <a href="{{ route('whishlist.addCart',['rowId' => $row->rowId]) }}"
                                               class="whish-to-cart"
                                            >
                                                Ajouter au panier
                                            </a>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Vous n'avez aucun produit dans votre liste de souhait</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
