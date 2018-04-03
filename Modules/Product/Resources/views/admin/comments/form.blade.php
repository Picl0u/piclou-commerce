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
                if(!empty($data->published)) {
                    $checked = 'checked="checked"';
                }
            ?>
            <input type="checkbox" name="published" {{ $checked }}> En ligne ?
        </label>
    </div>

    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('users', 'Utilisateurs') }}
               <select name="user_id" class="multiple-select">
                   @foreach($users as $user)
                       <?php
                           $selected="";
                            if($data->user_id == $user->id){
                                $selected='selected="selected"';
                            }
                       ?>
                       <option value="{{ $user->id }}" {{ $selected }}>
                           {{ $user->email }} - {{ $user->firstname }} {{ $user->lastname }}
                       </option>
                   @endforeach
               </select>
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('products', 'Produits') }}
                <select name="product_id" class="multiple-select">
                    @foreach($products as $product)
                        <?php
                        $selected="";
                        if($data->product_id == $product->id){
                            $selected='selected="selected"';
                        }
                        ?>
                        <option value="{{ $product->id }}" {{ $selected }}>
                            {{ $product->name }} - Ref : {{ $product->reference }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-item">
        {{ Form::label('comment', 'Commentaire') }}
        {{ Form::textarea('comment', $data->comment) }}
    </div>

</div>