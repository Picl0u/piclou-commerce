<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">Informations</a></li>
    </ul>
</nav>
<div id="infos">

    <div class="form-item">
        {{ Form::label('name', 'Nom de la page') }}
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
        {{ Form::label('description', 'Contenu') }}
        {{ Form::textarea('description', $data->description, ['class' => 'html-editor']) }}
    </div>

</div>
