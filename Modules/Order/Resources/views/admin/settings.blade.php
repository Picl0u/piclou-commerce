@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Configurer</span></li>
            <li><span>Paramètre du site</span></li>
            <li><span>Commandes</span></li>
        </ul>
    </nav>

    <h2>
        Commandes
        <span> - Gérez les paramètres pour vos commandes</span>
    </h2>
@endsection

@section('content')

    {!! Form::open(['route' => "settings.orders.store"]) !!}

    <nav class="tabs" data-component="tabs">
        <ul>
            <li class="active"><a href="#infos">Informations</a></li>
        </ul>
    </nav>

    <div id="infos">
        <div class="row gutters">
            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data['noAccount'])) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="noAccount" {{ $checked }}> Commande express ?
                </label>
                <div class="desc">Permet de commander sans la création d'un compte.</div>
            </div>
            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data['orderAgain'])) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="orderAgain" {{ $checked }}> Commander à nouveau ?
                </label>
                <div class="desc">Permet de commander à nouveau un produit depuis son ancienne commande.</div>
            </div>

            <div class="form-item col col-6">
                {{ Form::label('minAmmout', 'Montant minimum') }}
                {{ Form::text('minAmmout',$data['minAmmout']) }}
                <div class="desc">Montant total minimum requis pour valider une commande</div>
            </div>

            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data['stockBooked'])) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="stockBooked" {{ $checked }}> Retirer stock panier ?
                </label>
                <div class="desc">Permet de retirer les stocks disponible lors de la mise au panier d'un produit</div>
            </div>
            <div class="col col-6 form-item">
                {{ Form::label('countryId', 'Pays de livraison par défaut') }}
                {{ Form::select('countryId', $countriesArray, $data['countryId']) }}
            </div>

            <div class="form-item col col-6">
                {{ Form::label('freeShippingPrice', 'Frais de port offert') }}
                {{ Form::text('freeShippingPrice',$data['freeShippingPrice']) }}
                <div class="desc">
                    Si le prix total du panier est supérieur à ce prix, alors les frais de port sont offert.<br>
                    Si 0, alors les frais de port ne sont pas offert.
                </div>
            </div>


            <div class="form-item col col-12">
                {{ Form::label('productQuantityAlert', 'Alerte quantité produit') }}
                {{ Form::text('productQuantityAlert',$data['productQuantityAlert']) }}
                <div class="desc">
                    Si le stock du produit est inférieur ou égale à cette valeur, alors vous serez alerté par email.
                </div>
            </div>

            <div class="col col-6 form-item">
                {{ Form::label('cgvId', 'Page des CGV') }}
                {{ Form::select('cgvId', $contentsArray, $data['cgvId']) }}
            </div>

            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data['cgv'])) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="cgv" {{ $checked }}> Conditions générales de ventes
                </label>
            </div>


            <div class="col col-6 form-item">
                {{ Form::label('acceptId', 'Page paiement accepté') }}
                {{ Form::select('acceptId', $contentsArray, $data['acceptId']) }}
            </div>


            <div class="col col-6 form-item">
                {{ Form::label('refuseId', 'Page paiement refusé') }}
                {{ Form::select('refuseId', $contentsArray, $data['refuseId']) }}
            </div>

        </div>
    </div>


    <div class="form-buttons">
        {{ Form::submit('Enregistrer') }}
    </div>
    {!! Form::close() !!}
@endsection
