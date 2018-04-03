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
                    if(!empty($data->online)) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="online" {{ $checked }}> Compte activé ?
                </label>
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data->newsletter)) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="newsletter" {{ $checked }}> Abonné au newsletters?
                </label>
            </div>
        </div>
    </div>

    <div class="form-item form-checkboxes">
        <label class="checkbox">
            <?php
                $checked="";
                if($data->gender == "M"){
                    $checked="checked";
                }
            ?>
            <input type="radio" name="gender" value="M" {{ $checked }}>
            M.
        </label>
        <label class="checkbox">
            <?php
                $checked="";
                if($data->gender == "Mme"){
                    $checked="checked";
                }
            ?>
            <input type="radio" name="gender" value="Mme" {{ $checked }}>
            Mme
        </label>
    </div>

    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('firstname', 'Prénom') }}
                {{ Form::text('firstname', $data->firstname ) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('lastname', 'Nom') }}
                {{ Form::text('lastname', $data->lastname) }}
            </div>
        </div>

        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('email', trans('form.email')) }}
                {{ Form::email('email',  $data->email) }}
            </div>
        </div>

        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('password', trans('form.password')) }}
                {{ Form::password('password', null) }}
                <div class="desc">Laissez vide si pas de changement</div>
            </div>
        </div>

        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('role_id', 'Rôle') }}
                <select name="role_id">
                    @foreach($roles as $role)
                        <?php
                            $selected='';
                            if($data->role_id == $role->id) {
                                $selected='selected="selected"';
                            }
                        ?>
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

</div>
