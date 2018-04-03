<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">{{ __('content::admin.informations') }}</a></li>
        <li><a href="#seo">{{ __('content::admin.seo') }}</a></li>
    </ul>
</nav>
<div id="infos">
    <div class="row gutters">
        <div class="col col-3">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                        $checked="";
                        if(!empty($data->published)) {
                            $checked = 'checked="checked"';
                        }
                    ?>
                    <input type="checkbox" name="published" {{ $checked }}> {{ __('content::admin.published') }} ?
                </label>
            </div>
        </div>
        <div class="col col-3">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                        $checked="";
                        if(!empty($data->on_homepage)) {
                            $checked = 'checked="checked"';
                        }
                    ?>
                    <input type="checkbox" name="on_homepage" {{ $checked }}> {{ __('content::admin.homepage') }}
                </label>
                <div class="desc">{{ __('content::admin.homepage_desc') }}</div>
            </div>
        </div>
        <div class="col col-3">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data->on_menu)) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="on_menu" {{ $checked }}> {{ __('content::admin.menu') }}
                </label>
                <div class="desc">{{ __('content::admin.menu_desc') }}</div>
            </div>
        </div>
        <div class="col col-3">
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
                <div class="desc">{{ __('content::admin.footer_desc') }}</div>
            </div>
        </div>
    </div>

    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('name', __('content::admin.name')) }}
                {{ Form::text('name', $data->name) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('slug', __('content::admin.slug')) }}
                {{ Form::text('slug', $data->slug) }}
                <div class="desc">{{ __('content::admin.name_desc' )}}</div>
            </div>
        </div>
    </div>

    <div class="form-item">
        {{ Form::label('image', __('content::admin.image')) }}
        {{ Form::file('image')  }}

        @if($data->image)
            <div class="image-form">
                <img src="{{ resizeImage($data->image, 100, 100) }}"
                     alt="{{ $data->name }}"
                     class="remodalImg"
                     data-src="{{ asset($data->image) }}"
                >
            </div>
        @endif
    </div>

    <div class="form-item">
        {{ Form::label('category_id', __('content::admin.category')) }}

        {{ Form::select(
            'content_category_id',
            $categories,
            $data->content_category_id,
            ['placeholder' => __("content::admin.category_select")]
        ) }}

    </div>

    <div class="form-item">
        {{ Form::label('summary', __("content::admin.summary")) }}
        {{ Form::textarea('summary', $data->summary,['class' => 'html-editor']) }}
    </div>

    <div class="form-item">
        {{ Form::label('description', __("content::admin.description")) }}
        {{ Form::textarea('description', $data->description,['class' => 'html-editor']) }}
    </div>

</div>

<div id="seo">
    <div class="form-item">
        {{ Form::label('seo_title', __("content::admin.seo_title")) }}
        {{ Form::text('seo_title', $data->seo_title) }}
    </div>

    <div class="form-item">
        {{ Form::label('seo_description', __("content::admin.seo_description")) }}
        {{ Form::textarea('seo_description', $data->seo_description) }}
    </div>
</div>