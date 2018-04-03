@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Commandes</span></li>
            <li><span>Pays</span></li>
        </ul>
    </nav>

    <h2>
        Pays
        <span> - Gérez les pays de livraison pour votre boutique</span>
    </h2>
@endsection

@section('content')
    <div class="datatable-container">
        <table class="datatable display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Activer?</th>
                <th>Nom</th>
                <th>Code ISO</th>
                <th>Devise</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection

@section('style')
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@push('scripts')
    <script>
        jQuery(function() {
            jQuery('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax : "{{ route('orders.countries.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'activated', name: 'activated' },
                    { data: 'name', name: 'name' },
                    { data: 'iso_3166_2', name: 'iso_3166_2' },
                    { data: 'currency_symbol', name: 'currency_symbol' },
                    { data: 'actions', name: 'actions' },
                ],
                order: [[ 2, "asc" ]],
                columnDefs: [{
                    orderable: false,
                    targets: 5
                }],
                stateSave: true,
                responsive: true,
                scrollX: true,
                language : {
                    url : 'http://cdn.datatables.net/plug-ins/1.10.16/i18n/French.json'
                },
            });
        });
    </script>
@endpush
