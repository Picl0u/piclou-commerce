@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>{{ __("admin::navigation.personalize") }}</span></li>
            <li><span>{{ __("content::admin.navigation_pages") }}</span></li>
            <li><span>{{ __("content::admin.navigation_contents") }}</span></li>
            <li><span>Positions</span></li>
        </ul>
    </nav>

    <h2>
        Positions
        <span> - Gérez les positions de vos pages</span>
    </h2>
@endsection
@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.pages.contents.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    <div class="nested-section" data-url="{{ route('admin.pages.contents.positions.store') }}">
        {!!
            nestableExtends($datas)
            ->firstUlAttr('class', 'sortable')
            ->liSortable()
            ->renderAsHtml();
        !!}
    </div>
@endsection
