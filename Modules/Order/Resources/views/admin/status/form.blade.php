<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">Informations</a></li>
    </ul>
</nav>

<div id="infos">

    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data->order_accept)) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="order_accept" {{ $checked }}> Pour paiement accepté?
                </label>
                <div class="desc">Ce statut est pour identifier les paiements acceptés</div>
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data->order_refuse)) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="order_refuse" {{ $checked }}> Pour paiement refusé?
                </label>
                <div class="desc">Ce statut est pour identifier les paiements refusés</div>
            </div>
        </div>
    </div>

    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('name', 'Nom du statut') }}
                {{ Form::text('name', $data->name) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('color', 'Couleur du statut') }}
                {{ Form::text('color', $data->color) }}
            </div>
        </div>
    </div>
</div>
