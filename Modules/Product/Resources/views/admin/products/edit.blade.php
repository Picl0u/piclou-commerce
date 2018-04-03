@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Produits</span></li>
            <li><span>Modifier : {{ $data->name }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier le produit : {{ $data->name }}
        <span> - Modifier ce produit pour votre boutique</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("shop.products.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    <div class="translate-actions">
        {!! formTranslate(\Modules\Product\Entities\Product::class, $data)
        ->action('admin.shop.products.translate')
        ->render() !!}
    </div>

    {!! Form::open(['route' => ["shop.products.update", $data->uuid], 'files' => true]) !!}
    @include('product::admin.products.form',compact('data','products'))
    <div class="form-buttons">
        {{ Form::submit('Editer') }}
    </div>
    {!! Form::close() !!}

    <div class="remodal" data-remodal-id="modal-attributes" data-remodal-options="hashTracking: false">
        <div data-remodal-action="close" class="remodal-close"></div>
        <div class="modal-container"></div>
    </div>

@endsection
