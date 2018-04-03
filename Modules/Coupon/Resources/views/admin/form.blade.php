<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">{{ __("coupon::admin.informations") }}</a></li>
    </ul>
</nav>
<div id="infos">

    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('name', __("coupon::admin.name")) }}
                {{ Form::text('name', $data->name) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('coupon', __("coupon::admin.coupon")) }}
                {{ Form::text('coupon', $data->coupon) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('percent', __("coupon::admin.percent")) }}
                {{ Form::text('percent', $data->percent) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('price', __("coupon::admin.price")) }}
                {{ Form::text('price', $data->price) }}
            </div>
        </div>
        <div class="col col-12">
                {{ Form::hidden('use_max', $data->use_max) }}
            <div class="form-item">
                {{ Form::label('amount_min', __("coupon::admin.price_min")) }}
                {{ Form::text('amount_min', $data->amount_min) }}
            </div>
        </div>

        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('begin', __("coupon::admin.begin")) }}
                {{ Form::text('begin', $data->begin,["class" => 'datetime-picker']) }}
                <div class="desc">{{ __("coupon::admin.optional") }}</div>
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('end', __("coupon::admin.end")) }}
                {{ Form::text('end', $data->end,["class" => 'datetime-picker']) }}
                <div class="desc">{{ __("coupon::admin.optional") }}</div>
            </div>
        </div>

        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('users', __("coupon::admin.users")) }}
               <select name="users[]" multiple="multiple" class="multiple-select">
                   @foreach($users as $user)
                       <?php
                           $selected="";
                           foreach ($data->CouponUsers as $cu) {
                                if($cu->user_id == $user->id){
                                    $selected='selected="selected"';
                                }
                           }
                       ?>
                       <option value="{{ $user->id }}" {{ $selected }}>
                           {{ $user->email }} - {{ $user->firstname }} {{ $user->lastname }}
                       </option>
                   @endforeach
               </select>
                <div class="desc">{{ __("coupon::admin.users_desc") }}</div>
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('products', __("coupon::admin.products")) }}
                <select name="products[]" multiple="multiple" class="multiple-select">
                    @foreach($products as $product)
                        <?php
                        $selected="";
                        foreach ($data->CouponProducts as $cp) {
                            if($cp->product_id == $product->id){
                                $selected='selected="selected"';
                            }
                        }
                        ?>
                        <option value="{{ $product->id }}" {{ $selected }}>
                            {{ $product->name }} - Ref : {{ $product->reference }}
                        </option>
                    @endforeach
                </select>
                <div class="desc">{{ __("coupon::admin.products_desc") }}</div>
            </div>
        </div>
    </div>

</div>