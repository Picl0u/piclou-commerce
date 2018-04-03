@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Personnaliser</span></li>
            <li><span>Newsletter</span></li>
        </ul>
    </nav>

   <h2>
       Newsletter
      <span> - Gérez les inscriptions pour votre newsletter</span>
   </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.newsletter.create") }}">
            <i class="fas fa-plus"></i>
            Ajouter
        </a>
        <a href="{{ route("admin.newsletter.export") }}">
            <i class="fas fa-file-excel"></i>
            Exporter
        </a>
        <div class="clear"></div>
    </div>
    <div class="datatable-container">
        <table class="datatable display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Activé ?</th>
                <th>Email</th>
                <th>Prénom</th>
                <th>Nom</th>
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
                ajax : "{{ route('admin.newsletter.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'active', name: 'active' },
                    { data: 'email', name: 'email' },
                    { data: 'firstname', name: 'firstname' },
                    { data: 'lastname', name: 'lastname' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'actions', name: 'actions' }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 6
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
