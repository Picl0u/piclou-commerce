<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">Informations</a></li>
        <li><a href="#seo">Référencement</a></li>
    </ul>
</nav>
<div id="infos">
    <div class="row gutters">
        <div class="col col-6">
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
        </div>
        <div class="col col-6">
            <div class="form-item">
                <label class="checkbox">
                    <?php
                        $checked="";
                        if(!empty($data->on_homepage)) {
                            $checked = 'checked="checked"';
                        }
                    ?>
                    <input type="checkbox" name="on_homepage" {{ $checked }}> Sur l'accueil ?
                </label>
                <div class="desc">Affiche la catégorie sur la page d'accueil</div>
            </div>
        </div>
    </div>

    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('name', 'Nom de la catégorie') }}
                {{ Form::text('name', $data->name) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('slug', 'Lien de la categorie') }}
                {{ Form::text('slug', $data->slug) }}
                <div class="desc">URL simplifiée</div>
            </div>
        </div>
    </div>

    <div class="form-item">
        {{ Form::label('image', 'Image de la categorie') }}
        {{ Form::file('image')  }}
        <div class="desc">Image pour l'accueil et/ou le menu</div>

        @if($data->image)
            <div class="image-form">
                <img src="{{ resizeImage($data->image, 100, 100) }}"
                     alt="{{ $data->name }}"
                     class="remodalImg"
                     data-src="{{ asset($data->image) }}"
                >
                <a href="{{ route("shop.categories.image.delete",['id' => $data->id]) }}"
                   class="delete-image confirm-alert">
                    Supprimer
                </a>
            </div>
        @endif
    </div>


    <div class="form-item">
        {{ Form::label('imageList', 'Image liste produit') }}
        {{ Form::file('imageList')  }}
        <div class="desc">Image s'affichant en haut de la liste des produits</div>
            @if($data->imageList)
                <div class="image-form">
                    <img src="{{ resizeImage($data->imageList, 100, 100) }}"
                         alt="{{ $data->name }}"
                         class="remodalImg"
                         data-src="{{ asset($data->imageList) }}"
                    >
                    <a href="{{ route("shop.categories.imageList.delete",['id' => $data->id]) }}"
                       class="delete-image confirm-alert">
                        Supprimer
                    </a>
                </div>
            @endif
        </div>

    <div class="form-item">
        {{ Form::label('description', 'Description de la categorie') }}
        {{ Form::textarea('description', $data->description,['class' => 'html-editor']) }}
    </div>

</div>

<div id="seo">
    <div class="form-item">
        {{ Form::label('seo_title', 'SEO Title') }}
        {{ Form::text('seo_title', $data->seo_title) }}
    </div>

    <div class="form-item">
        {{ Form::label('seo_description', 'SEO Description') }}
        {{ Form::textarea('seo_description', $data->seo_description) }}
    </div>
</div>