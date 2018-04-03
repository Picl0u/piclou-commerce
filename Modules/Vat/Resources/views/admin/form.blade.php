<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">Informations</a></li>
    </ul>
</nav>
<div id="infos">
    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('name', 'Nom de la taxe') }}
                {{ Form::text('name', $data->name) }}
                <div class="desc">Exemple : TVA FR 20%</div>
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('percent', 'Pourcentage') }}
                {{ Form::text('percent', $data->percent) }}
                <div class="desc">Ne pas mettre %</div>
            </div>
        </div>
    </div>


</div>
