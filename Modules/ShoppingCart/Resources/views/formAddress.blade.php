{!! Form::open(['route' => 'cart.user.address.store']) !!}
    <label>{{ trans('form.civility') }}</label>
    <div class="form-item form-checkboxes">
        <label class="checkbox">
            <?php
            $checked = "";
            if($user->gender == "M"){
                $checked='checked="checked"';
            }
            ?>
            <input type="radio" name="gender" value="M" {{ $checked }}>
            M.
        </label>
        <label class="checkbox">
            <?php
            $checked = "";
            if($user->gender == "Mme"){
                $checked='checked="checked"';
            }
            ?>
            <input type="radio" name="gender" value="Mme" {{ $checked }}>
            Mme
        </label>
    </div>

    <div class="form-item">
        {{ Form::label('firstname', trans('form.firstname')) }}
        {{ Form::text('firstname', $user->firstname) }}
    </div>

    <div class="form-item">
        {{ Form::label('lastname', trans('form.lastname')) }}
        {{ Form::text('lastname', $user->lastname) }}
    </div>

    <div class="form-item">
        {{ Form::label('address', trans('form.address')) }}
        {{ Form::text('address', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('additional_address', trans('form.additional_address')) }}
        {{ Form::text('additional_address', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('zip_code', trans('form.zip_code')) }}
        {{ Form::text('zip_code', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('city', trans('form.city')) }}
        {{ Form::text('city', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('phone', trans('form.phone')) }}
        {{ Form::text('phone', null) }}
    </div>

    <div class="form-item">
        {{ Form::label('country_id', trans('form.country')) }}

        <select name="country_id" id="country_id">
            @foreach($countries as $country)
                <?php
                $selected="";
                if($country->id == 250){
                    $selected='selected="selected"';
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
            <input type="checkbox" name="billing" checked="checked">
            Utiliser cette adresse également comme adresse de facturation
        </label>
    </div>

    <button type="submit">
        {{ trans('form.register') }}
    </button>

{!! Form::close() !!}