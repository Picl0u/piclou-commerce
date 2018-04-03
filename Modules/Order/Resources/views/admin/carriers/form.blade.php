<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">Informations</a></li>
        <li><a href="#prices">Destinations et côuts</a></li>
        <!--<li><a href="#weight">Tailles, poids</a></li>-->
    </ul>
</nav>
<div id="infos">
    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data->published)) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="published" {{ $checked }}> En ligne ?
                </label>
            </div>
        </div>

        <div class="col col-6">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data->default)) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="default" {{ $checked }}> Transporteur par défaut ?
                </label>
            </div>
        </div>
    </div>

    <div class="form-item">
        {{ Form::label('name', 'Nom du transporteur') }}
        {{ Form::text('name', $data->name) }}
    </div>

    <div class="form-item">
        {{ Form::label('default_price', 'Prix par défaut') }}
        {{ Form::text('default_price', $data->default_price) }}
    </div>

    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('delay', 'Délais de livraison') }}
                {{ Form::text('delay', $data->delay) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('url', 'Url de suivis') }}
                {{ Form::text('url', $data->url) }}
                <div class="desc">URL de suivis des colis : http://www.exemple.fr/track.php?num=</div>
            </div>
        </div>
    </div>

    <div class="form-item">
        {{ Form::label('image', 'Logo du transporteur') }}
        {{ Form::file('image')  }}

        @if($data->image)
            <div class="image-form">
                <img src="{{ resizeImage($data->image, 100, 100) }}"
                     alt="{{ $data->name }}"
                     class="remodalImg"
                     data-src="{{ asset($data->image) }}"
                >
            </div>
        @endif
    </div>

</div>

<div id="prices">
    <div class="row gutters">

        <div class="col col-4">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                        $checked="";
                        if(!empty($data->free)) {
                            $checked = 'checked="checked"';
                        }
                    ?>
                    <input type="checkbox" name="free" {{ $checked }}> Livraison gratuite ?
                </label>
            </div>
        </div>

        <div class="col col-4 hide">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                        $checked="";
                        if(!empty($data->weight)) {
                            $checked = 'checked="checked"';
                        }
                    ?>
                    <input type="radio" name="type_shipping" {{ $checked }} value="weight">
                    En fonction du poids total
                </label>
            </div>
        </div>

        <div class="col col-4">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                        $checked="";
                        if(!empty($data->price)) {
                            $checked = 'checked="checked"';
                        } elseif(empty($data->price) && empty($data->weight)){
                            $checked = 'checked="checked"';
                        }
                    ?>
                    <input type="radio" name="type_shipping" {{ $checked }} value="price">
                    En fonction du prix total
                </label>
            </div>
        </div>

    </div>
    <label>Plages</label>
    <div class="carriers-plages">
        @include('order::admin.carriers.plages.price', compact('data','countries'))
    </div>

</div>

<div id="weight" class="hide">

    <div class="form-item">
        {{ Form::label('max_weight', 'Poids maximum du paquet (kg)') }}
        {{ Form::text('max_weight', $data->max_weight) }}
    </div>

    <div class="form-item">
        {{ Form::label('max_width', 'Largeur maximum du paquet (cm)') }}
        {{ Form::text('max_width', $data->max_width) }}
    </div>

    <div class="form-item">
        {{ Form::label('max_height', 'Hauteur maximum du paquet (cm)') }}
        {{ Form::text('max_height', $data->max_height) }}
    </div>

    <div class="form-item">
        {{ Form::label('max_length', 'Profondeur maximum du paquet (cm)') }}
        {{ Form::text('max_length', $data->max_length) }}
    </div>

</div>
