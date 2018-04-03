<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">Informations</a></li>
    </ul>
</nav>

<div id="infos">
    <div class="form-item">
        <label class="checkbox">
            <?php
                $checked="";
                if(!empty($data->active)) {
                    $checked = 'checked="checked"';
                }
            ?>
            <input type="checkbox" name="active" {{ $checked }}> En ligne ?
        </label>
    </div>
    <div class="row gutters">

        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('firstname', 'Prénom') }}
                {{ Form::text('firstname', $data->firstname) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('lastname', 'Nom') }}
                {{ Form::text('lastname', $data->lastname) }}
            </div>
        </div>

    </div>

    <div class="form-item">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', $data->email) }}
    </div>

</div>
