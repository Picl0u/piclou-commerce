<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">Informations</a></li>
    </ul>
</nav>

<div id="infos">

    <div class="row gutters">

        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('name', 'Nom') }}
                {{ Form::text('name', $data->name) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('guard_name', 'Nom sécurisé') }}
                {{ Form::text('guard_name', $data->guard_name) }}
            </div>
        </div>

    </div>

</div>
