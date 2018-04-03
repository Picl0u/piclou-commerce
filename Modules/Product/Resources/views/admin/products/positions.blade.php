@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Catégories</span></li>
            <li><span>Positions</span></li>
        </ul>
    </nav>

    <h2>
        Positions
        <span> - Gérez les positions de vos produits</span>
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

    <div class="nested-section" data-url="{{ route('shop.products.positions.store') }}">
        {!!
            nestableExtends($datas)
            ->firstUlAttr('class', 'sortable')
            ->liSortable()
            ->renderAsHtml();
        !!}
    </div>
@endsection
