@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Personnaliser</span></li>
            <li><span>Administrateurs</span></li>
            <li><span>Rôles</span></li>
        </ul>
    </nav>

   <h2>
       Rôles
      <span> - Gérez les rôles pour vos administrateurs</span>
   </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.roles.create") }}">
            <i class="fas fa-plus"></i>
            Ajouter
        </a>

        <div class="clear"></div>
    </div>
    <div class="datatable-container">
        <table class="datatable display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
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
                ajax : "{{ route('admin.roles.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'actions', name: 'actions' }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 3
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
