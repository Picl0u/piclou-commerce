@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Personnaliser</span></li>
            <li><span>Slider</span></li>
            <li><span>Positions</span></li>
        </ul>
    </nav>

    <h2>
        Positions
        <span> - Gérez les positions des slides de votre slider</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("sliders.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    <div class="nested-section" data-url="{{ route('sliders.positions.store') }}">
        {!!
            nestableExtends($datas)
            ->firstUlAttr('class', 'sortable')
            ->liSortable()
            ->renderAsHtml();
        !!}
    </div>
@endsection
