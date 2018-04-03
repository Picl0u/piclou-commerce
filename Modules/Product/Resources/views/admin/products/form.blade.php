<nav class="tabs" data-component="tabs">
    <ul>
        <li class="active"><a href="#infos">Informations</a></li>
        <li><a href="#prices">Prix</a></li>
        <li><a href="#transport">Livraison</a></li>
        <li><a href="#attributes">Déclinaisons</a></li>
        <li><a href="#images">Images</a></li>
        <li><a href="#seo">Référencement</a></li>
    </ul>
</nav>
<div id="infos">
    <div class="row gutters">
        <div class="col col-6 form-item">
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
        <div class="col col-6 form-item">
            <label class="checkbox">
                <?php
                $checked="";
                if(!empty($data->week_selection)) {
                    $checked = 'checked="checked"';
                }
                ?>
                <input type="checkbox" name="week_selection" {{ $checked }}> Sélection de la semaine ?
            </label>
        </div>
    </div>

    <div class="row gutters">
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('name', 'Titre du produit') }}
                {{ Form::text('name', $data->name) }}
            </div>
        </div>
        <div class="col col-6">
            <div class="form-item">
                {{ Form::label('slug', 'Lien du produit') }}
                {{ Form::text('slug', $data->slug) }}
                <div class="desc">URL simplifiée</div>
            </div>
        </div>
    </div>

    <div class="row gutters">
        <div class="col col-3">
            <div class="form-item">
                {{ Form::label('reference', 'Référence du produit') }}
                {{ Form::text('reference', $data->reference) }}
            </div>
        </div>
        <div class="col col-3">
            <div class="form-item">
                {{ Form::label('ean_code', 'Code-barre EAN-13 ou JAN') }}
                {{ Form::text('ean_code', $data->ean_code) }}
            </div>
        </div>
        <div class="col col-3">
            <div class="form-item">
                {{ Form::label('upc_code', 'Code-barre UPC') }}
                {{ Form::text('upc_code', $data->upc_code) }}
            </div>
        </div>
        <div class="col col-3">
            <div class="form-item">
                {{ Form::label('isbn_code', 'Code-barre ISBN') }}
                {{ Form::text('isbn_code', $data->isbn_code) }}
            </div>
        </div>
    </div>

    <div class="form-item">
        {{ Form::label('stock_brut', 'Quantité') }}
        {{ Form::text('stock_brut', $data->stock_brut) }}
    </div>

    <div class="form-item">
        {{ Form::label('category_id', 'Catégorie principal') }}
        {!! nestableExtends($categories)->attr(['name'=>'shop_category_id'])->selected($data->shop_category_id)->renderAsDropdown() !!}
    </div>

    <div class="form-item">
        {{ Form::label('categories', 'Catégories') }}
        <div class="checkboxes-tree">
            <?php
               $tree = new \App\Http\Picl0u\TreeCheckboxes('categories','shop_category_id',$data->ProductsHasCategories);
                foreach($categories as $category) {
                    $tree->addRow($category['id'], (empty($category['parent_id']))?null:$category['parent_id'], $category['name']);
                }
                echo $tree->generateList();
            ?>
        </div>
    </div>

    <div class="form-item">
        {{ Form::label('associates', 'Produits associés') }}
        <select name="associates[]" multiple="multiple" class="multiple-select">
            @foreach($products as $product)
                <?php
                $selected="";
                foreach ($data->ProductsAssociates as $p) {
                    if($p->product_id == $product->id){
                        $selected='selected="selected"';
                    }
                }
                ?>
                <option value="{{ $product->id }}" {{ $selected }}>
                    {{ $product->name }} - ref : {{ $product->reference }}
                </option>
            @endforeach
        </select>
        <div class="desc">Permet de gérer les produits dans "Vous aimerez aussi"</div>
    </div>

    <div class="form-item">
        {{ Form::label('summary', 'Description courte du produit') }}
        {{ Form::textarea('summary', $data->summary, ['class' => 'html-editor']) }}
    </div>

    <div class="form-item">
        {{ Form::label('description', 'Description du produit') }}
        {{ Form::textarea('description', $data->description, ['class' => 'html-editor']) }}
    </div>

</div>

<div id="prices">

    <div class="form-item">
        {{ Form::label('price_ht', 'Prix HT') }}
        {{ Form::text('price_ht', $data->price_ht) }}
    </div>
    <div class="form-item">
        {{ Form::label('vat_id', 'Taxe') }}
        <select id="vat_id" name="vat_id">
            {{ $selected = "" }}
            @foreach($vats as $vat)
                @if($vat->id == $data->vat_id)
                    {{ $selected='selected="selected' }}
                @endif
                <option value="{{ $vat->id }}" data-taux="{{$vat->percent}}" {{ $selected }}>
                    {{ $vat->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-item">
        {{ Form::label('price_ttc', 'Prix TTC') }}
        {{ Form::text('price_ttc', $data->price_ttc) }}
        <div class="desc">
            Le montant TTC sera recalculée lors de la finalisation de la commande en fonction de pays de livraison
        </div>
    </div>

    <div class="form-item">
        {{ Form::label('reduce_date_begin', 'Date début réduction') }}
        {{ Form::text('reduce_date_begin', $data->reduce_date_begin, ['class' => 'datetime-picker']) }}
        <div class="desc">
            Date du début du programme de réduction ou de vente-flash
        </div>
    </div>
    <div class="form-item">
        {{ Form::label('reduce_date_end', 'Date fin réduction') }}
        {{ Form::text('reduce_date_end', $data->reduce_date_end, ['class' => 'datetime-picker']) }}
        <div class="desc">
            Date de fin du programme de réduction ou de vente-flash
        </div>
    </div>
    <div class="form-item">
        {{ Form::label('reduce_price', 'Réduction par prix') }}
        {{ Form::text('reduce_price', $data->reduce_price) }}
    </div>
    <div class="form-item">
        {{ Form::label('reduce_percent', 'Réduction par pourcentage') }}
        {{ Form::text('reduce_percent', $data->reduce_percent) }}
    </div>

</div>

<div id="transport">
    <div class="form-item">
        {{ Form::label('weight', 'Poids (en kg)') }}
        {{ Form::text('weight', $data->weight) }}
    </div>
    <div class="form-item">
        {{ Form::label('height', 'Hauteur (en cm)') }}
        {{ Form::text('height', $data->height) }}
    </div>
    <div class="form-item">
        {{ Form::label('length', 'Profondeur (en cm)') }}
        {{ Form::text('length', $data->length) }}
    </div>
    <div class="form-item">
        {{ Form::label('width', 'Largeur (en cm)') }}
        {{ Form::text('width', $data->width) }}
    </div>
</div>

<div id="images">
    <div class="form-item">
        {{ Form::label('image', 'Vignette du produit') }}
        <div class="row gutters">
            <div class="col col-9">
                <table class="bordered striped table-medias">
                    <thead>
                    <tr>
                        <th>Medias</th>
                        <th>Balise alt</th>
                        <th>Lien</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($data->image)
                        <tr>
                            <td>
                                <img src="{{ resizeImage($data->getMedias('image','src'), 40, 40) }}"
                                     alt="{{ $data->name }}"
                                     class="remodalImg"
                                     data-src="{{ asset($data->getMedias('image','src')) }}"
                                >
                            </td>
                            <td class="alt">
                                {{ $data->getMedias('image','alt') }}
                            </td>
                            <td>
                                <a href="{{ asset($data->getMedias('image','src')) }}" target="_blank">
                                    Afficher
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('shop.products.image.update',['id' => $data->id]) }}"
                                   class="edit-medias"
                                   data-id="{{$data->id}}"
                                   data-remodal-target="medias-update"
                                >
                                    <i class="fas fa-pencil-alt"></i></i> {{ __('admin::actions.edit') }}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    @endif
                </table>
            </div>
            <div class="col col-3">
                {{ Form::file('image')  }}
                <div class="desc">
                    Images pour la présentation de votre produit
                </div>
            </div>
        </div>

    </div>
    <div class="form-item">
        {{ Form::label('images', 'Images du produit') }}
        <div class="row gutters">
            <div class="col col-9">
                <table class="bordered striped table-medias">
                    <thead>
                        <tr>
                            <th class="w20">Medias</th>
                            <th class="w30">Balise alt</th>
                            <th class="w20">Lien</th>
                            <th class="w30">Actions</th>
                        </tr>
                    </thead>
                </table>
                @if(count($data->ProductsHasMedias)> 0)
                <div class="table-sortable" data-url="{{ route('shop.products.images.positions', ['id'=> $data->id]) }}">
                    <ul class="sortable">
                        @foreach($data->ProductsHasMedias as $media)
                            <li id="menuItem_'{{ $media->id }}'">
                                <span class="w20">
                                    <img src="{{ resizeImage($media->getMedias('image','src'), 40, 40) }}"
                                         alt="{{ $data->name }}"
                                         class="remodalImg"
                                         data-src="{{ asset($media->getMedias('image','src')) }}"
                                    >
                                </span>
                                <span class="alt w30">
                                    {{ $media->getMedias('image','alt') }}
                                </span>
                                <span class="w20">
                                    <a href="{{ asset($media->getMedias('image','src')) }}" target="_blank">
                                        Afficher
                                    </a>
                                </span>
                                <span class="w30">
                                    <a href="{{ route('shop.products.images.update',['id' => $media->id]) }}"
                                       class="edit-medias"
                                       data-remodal-target="medias-update"
                                       data-id="{{$media->id}}"
                                    >
                                        <i class="fas fa-pencil-alt"></i> {{ __('admin::actions.edit') }}
                                    </a>
                                    <a
                                            href="{{ route('shop.products.image.delete',['id' => $media->id]) }}"
                                            class="image-delete confirm-alert"
                                    >
                                        <i class="fas fa-trash"></i> {{ __('admin::actions.delete') }}
                                    </a>
                                </span>
                                <div class="clear"></div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            <div class="col col-3">
                {{ Form::file('images[]', ['multiple'=>'multiple'])  }}
                <div class="desc">
                    Images pour votre fiche produits
                </div>
            </div>
        </div>

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

<div id="attributes">
    <div class="declinaisons-actions">
        @if(!is_null($data->id))
            <a href="{{ route('admin.products.attribute.add', ['id' => $data->id]) }}"
               class="add-new-declinaison"
            >
                <i class="fas fa-plus"></i>
                {{ __('admin::actions.add') }}
            </a>
        @else
            <p>
                <i class="fas fa-exclamation-triangle"></i>
                Vous devez enregistrer votre produit avant d'ajouter des déclinaisons.
            </p>
        @endif
    </div>
    <table class="bordered striped table-declinaisons">
        <thead>
        <tr>
            <th class="w20">Déclinaisons</th>
            <th class="w30">Référence</th>
            <th class="w20">Quantité</th>
            <th class="w30">Actions</th>
        </tr>
        </thead>
        <tbody>
            @foreach($data->ProductsAttributes as $attribute)
                <tr>
                    <td>
                        @php $declinaisons = $attribute->getValues('declinaisons'); @endphp
                        @foreach($declinaisons as $key => $value)
                            <span class="declinaison-value"><strong>{{ $key }} :</strong> {{ $value }}</span>
                        @endforeach
                    </td>
                    <td>
                        {{ $attribute->reference }}
                    </td>
                    <td>
                        {{ $attribute->stock_brut }}
                    </td>
                    <td class="table-actions">
                        <a href="{{ route('admin.products.attribute.edit',[
                                'id' => $data->id,
                                'uuid' => $attribute->uuid
                            ]) }}"
                           class="edit-declinaison">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('admin::actions.edit') }}
                        </a>
                        <a href="{{ route('admin.products.attribute.delete',['uuid' => $attribute->uuid]) }}"
                           class="delete-declinaison">
                            <i class="fas fa-trash"></i>
                            {{ __('admin::actions.delete') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>