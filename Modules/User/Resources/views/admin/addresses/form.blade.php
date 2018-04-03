<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">Informations</a></li>
    </ul>
</nav>

<div id="infos">

    <div class="form-item">
        {{ Form::label('user_id', 'Utilisateur') }}

        <select name="user_id" id="user_id">
            @foreach($users as $user)
                <?php
                    $selected="";
                    if($user->id == $data->user_id){
                        $selected='selected="selected"';
                    }
                    ?>
                <option value="{{ $user->id }}" {{ $selected }}>
                    {{ $user->firstname }} {{ $user->lastname }}
                </option>
            @endforeach
        </select>

    </div>

    <label>{{ trans('form.civility') }}</label>
    <div class="form-item form-checkboxes">
        <label class="checkbox">
            <?php
                $checked = "";
                if($data->gender == "M"){
                    $checked='checked="checked"';
                }
            ?>
            <input type="radio" name="gender" value="M" {{ $checked }}>
            M.
        </label>
        <label class="checkbox">
            <?php
                $checked = "";
                if($data->gender == "Mme"){
                    $checked='checked="checked"';
                }
            ?>
            <input type="radio" name="gender" value="Mme" {{ $checked }}>
            Mme
        </label>
    </div>

    <div class="form-item">
        {{ Form::label('firstname', trans('form.firstname')) }}
        {{ Form::text('firstname', $data->firstname) }}
    </div>

    <div class="form-item">
        {{ Form::label('lastname', trans('form.lastname')) }}
        {{ Form::text('lastname', $data->lastname) }}
    </div>

    <div class="form-item">
        {{ Form::label('address', trans('form.address')) }}
        {{ Form::text('address', $data->address) }}
    </div>

    <div class="form-item">
        {{ Form::label('additional_address', trans('form.additional_address')) }}
        {{ Form::text('additional_address', $data->additional_address) }}
    </div>

    <div class="form-item">
        {{ Form::label('zip_code', trans('form.zip_code')) }}
        {{ Form::text('zip_code', $data->zip_code) }}
    </div>

    <div class="form-item">
        {{ Form::label('city', trans('form.city')) }}
        {{ Form::text('city', $data->city) }}
    </div>

    <div class="form-item">
        {{ Form::label('phone', trans('form.phone')) }}
        {{ Form::text('phone', $data->phone) }}
    </div>

    <div class="form-item">
        {{ Form::label('country_id', trans('form.country')) }}

        <select name="country_id" id="country_id">
            @foreach($countries as $country)
                <?php
                $selected="";
                if(!empty($data->country_id)) {
                    if($country->id == $data->country_id){
                        $selected='selected="selected"';
                    }
                } else{
                    if($country->id == setting('orders.countryId')){
                        $selected='selected="selected"';
                    }
                }
                ?>
                <option value="{{ $country->id }}" {{ $selected }}>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>

    </div>

    <div class="form-item">
        <label class="checkbox">
            <?php
            $checked='checked';
            if(!empty($data->address)){
                $checked='';
                if(!empty($data->billing)){
                    $checked='checked';
                }
            }
            ?>
            <input type="checkbox" name="billing" {{ $checked }}>
            Utiliser cette adresse également comme adresse de facturation
        </label>
    </div>

</div>
