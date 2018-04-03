@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Catégories</span></li>
            <li><span>Modifier : {{ $data->name }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier la catégorie : {{ $data->name }}
        <span> - Modifier la catégorie pour votre boutique</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("shop.categories.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    <div class="translate-actions">
        {!! formTranslate(\Modules\Product\Entities\ShopCategory::class, $data)
        ->action('admin.shop.categories.translate')
        ->render() !!}
    </div>

    {!! Form::open(['route' => ["shop.categories.update", $data->uuid], 'files' => true]) !!}
    @include('product::admin.categories.form',compact('data'))
    <div class="form-buttons">
        {{ Form::submit('Editer') }}
    </div>
    {!! Form::close() !!}
@endsection
