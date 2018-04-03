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

    <div class="form-item">
        {{ Form::label('name', 'Titre de la slide') }}
        {{ Form::text('name', $data->name) }}
    </div>

    <div class="form-item">
        {{ Form::label('image', 'Image') }}
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
        {{ Form::label('link', 'Lien vers une page') }}
        {{ Form::text('link', $data->link) }}
    </div>

    <div class="form-item">
        {{ Form::label('position', 'Position') }}
        {{  Form::select(
            'position',
            ['left' => 'Gauche', 'center' => 'Centre', 'right' => 'Droite'],
            $data->position
        ) }}
        <div class="desc">Position de la description dans la slide.</div>
    </div>

    <div class="form-item">
        {{ Form::label('description', 'Description') }}
        {{ Form::textarea('description', $data->description, ['class' => 'html-editor']) }}
        <div class="desc">Description s'affichant dans votre slide</div>
    </div>

</div>
