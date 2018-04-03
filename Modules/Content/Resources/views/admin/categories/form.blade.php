<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">{{ __('content::admin.informations') }}</a></li>
    </ul>
</nav>
<div id="infos">
    <div class="form-item">
        <label class="checkbox">
            <?php
                $checked="";
                if(!empty($data->on_footer)) {
                    $checked = 'checked="checked"';
                }
            ?>
            <input type="checkbox" name="on_footer" {{ $checked }}> {{ __('content::admin.footer') }}
        </label>
        <div class="desc">{{ __("content::admin.footer_category_desc") }}</div>
    </div>

    <div class="form-item">
        {{ Form::label('name', __('content::admin.category_name')) }}
        {{ Form::text('name', $data->name) }}
    </div>

</div>