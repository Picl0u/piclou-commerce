@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Catégories</span></li>
        </ul>
    </nav>

   <h2>
      Catégories
      <span> - Gérez les catégories pour votre boutique</span>
   </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("shop.categories.create") }}">
            <i class="fas fa-plus"></i>
            Ajouter
        </a>
        <a href="{{ route("shop.categories.positions") }}">
            <i class="fas fa-list-ol"></i>
            Positions
        </a>
        <div class="clear"></div>
    </div>
    <div class="datatable-container">
        <table class="datatable display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>En ligne</th>
                <th>Image</th>
                <th>Titre</th>
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
                ajax : "{{ route('shop.categories.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'published', name: 'published' },
                    { data: 'image', name: 'image' },
                    { data: 'name', name: 'name' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'actions', name: 'actions' }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 5
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
