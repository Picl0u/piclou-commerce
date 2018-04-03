@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Commandes</span></li>
            <li><span>Transporteurs</span></li>
        </ul>
    </nav>

    <h2>
        Transporteurs
        <span> - Gérez les transporteurs pour vos commandes</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("orders.carriers.create") }}">
            <i class="fas fa-plus"></i>
            Ajouter
        </a>
    </div>

    @if(empty($countDefault))
        <div class="message warning">
            <h6>Attention !</h6>
            <p>Vous devez obligatoirement avoir un transporteur par défaut.</p>
        </div>
    @else
        @if($countDefault > 1)
            <div class="message warning">
                <h6>Attention !</h6>
                <p>
                    Vous devez obligatoirement avoir un transporteur par défaut.<br>
                    {{ $countDefault }} transporteurs sont séléctionnés comme transporteur par défaut
                </p>
            </div>
        @endif
    @endif

    <div class="datatable-container">
        <table class="datatable display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>En ligne</th>
                <th>Défaut</th>
                <th>Image</th>
                <th>Titre</th>
                <th>Délais</th>
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
                ajax : "{{ route('orders.carriers.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'published', name: 'published' },
                    { data: 'default', name: 'default' },
                    { data: 'image', name: 'image' },
                    { data: 'name', name: 'name' },
                    { data: 'delay', name: 'delay' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'actions', name: 'actions' }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 7
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
