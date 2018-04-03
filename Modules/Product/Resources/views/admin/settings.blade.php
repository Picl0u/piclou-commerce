@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Configurer</span></li>
            <li><span>Paramètre du site</span></li>
            <li><span>Produits</span></li>
        </ul>
    </nav>

    <h2>
        Produits
        <span> - Gérez les paramètres pour vos produits</span>
    </h2>
@endsection

@section('content')

    {!! Form::open(['route' => "settings.products.store"]) !!}

    <nav class="tabs" data-component="tabs">
        <ul>
            <li class="active"><a href="#infos">Informations</a></li>
        </ul>
    </nav>

    <div id="infos">

        <div class="form-item">
            {{ Form::label('paginate', 'Produit par page') }}
            {{ Form::text('paginate',$data['paginate']) }}
            <div class="desc">Nombre de produit par page</div>
        </div>
        <div class="row gutters">
            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                        $checked="";
                        if(!empty($data['commentEnable'])) {
                            $checked = 'checked="checked"';
                        }
                    ?>
                    <input type="checkbox" name="commentEnable" {{ $checked }}> Activer les commentaires ?
                </label>
            </div>
            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                        $checked="";
                        if(!empty($data['socialEnable'])) {
                            $checked = 'checked="checked"';
                        }
                    ?>
                    <input type="checkbox" name="socialEnable" {{ $checked }}> Activer partage réseaux sociaux ?
                </label>
            </div>
            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data['zoomEnable'])) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="zoomEnable" {{ $checked }}> Activer le zoom sur les images ?
                </label>
            </div>
            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data['modalEnable'])) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="modalEnable" {{ $checked }}> Activer la modal pour les images ?
                </label>
            </div>
        </div>

        <div class="row gutters">
            <div class="col col-6 form-item">
                {{ Form::label('orderField', 'Tri par défaut') }}
                {{  Form::select(
                    'orderField',
                    [
                        'order' => 'Positions',
                        'price_ttc' => 'Prix du produit',
                        'created_at' => 'Date d\'ajout',
                        'updated_at' => 'Date de modification',
                        'stock_available' => 'Quantité de produit',
                        'reference' => 'Référence du produit',
                    ],
                    $data['orderField']
                ) }}
                <div class="desc">Tri par défaut pour la liste des produits</div>
            </div>

            <div class="col col-6 form-item">
                {{ Form::label('orderDirection', 'Ordre par défaut') }}
                {{  Form::select(
                    'orderDirection',
                    ['asc' => 'Croissant', 'desc' => 'Décroissant'],
                    $data['orderDirection']
                ) }}
                <div class="desc">Ordre de tri par défaut pour la liste des produits</div>
            </div>

        </div>

    </div>


    <div class="form-buttons">
        {{ Form::submit('Enregistrer') }}
    </div>
@endsection
