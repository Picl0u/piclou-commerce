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
        <span> - Gérez les positions de vos catégories</span>
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

    {!! Form::open(['route' => "shop.categories.store"]) !!}
        <div class="nested-section">
            <?php
            $tree = new \App\Http\Iklass\Tree('ol','sortable');
            foreach($datas as $data) {
                $tree->addRow($data->id, $data->parent_id, $data->name);
            }
            echo $tree->generateList();
            ?>
        </div>

        <div class="form-buttons">
            {{ Form::submit('Modifier') }}
        </div>
    {!! Form::close() !!}
@endsection
