@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Commandes</span></li>
            <li><span>Commandes</span></li>
        </ul>
    </nav>

   <h2>
       Commandes
      <span> - Gérez les commandes pour votre boutique</span>
   </h2>
@endsection

@section('content')
    <div class="datatable-container">
        <table class="datatable display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Référence</th>
                <th>Client inscrit</th>
                <th>Client</th>
                <th>Livraison</th>
                <th>Total</th>
                <th>Etat</th>
                <th>Dernière modification</th>
                <th>Action</th>
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
                ajax : "{{ route('orders.orders.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'reference', name: 'reference' },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'user_firstname', name: 'user_firstname' },
                    { data: 'delivery_country_name', name: 'delivery_country_name' },
                    { data: 'price_ttc', name: 'price_ttc' },
                    { data: 'status_id', name: 'status_id' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'actions', name: 'actions' }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 8
                }],
                order: [[ 0, "desc" ]],
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
