@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>{{ __("admin::navigation.personalize") }}</span></li>
            <li><span>{{ __("content::admin.navigation_pages") }}</span></li>
            <li><span>{{ __("content::admin.navigation_contents") }}</span></li>
        </ul>
    </nav>

   <h2>
       {{ __("content::admin.navigation_contents") }}
      <span> - {{ __("content::admin.navigation_contents") }}</span>
   </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.pages.contents.create") }}">
            <i class="fas fa-plus"></i>
            {{ __("admin::actions.add") }}
        </a>
        <a href="{{ route("admin.pages.contents.positions") }}">
            <i class="fas fa-list-ol"></i>
            {{ __("admin::actions.positions") }}
        </a>
        <div class="clear"></div>
    </div>
    <div class="datatable-container">
        <table class="datatable display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>{{ __("content::admin.id") }}</th>
                <th>{{ __("content::admin.published") }}</th>
                <th>{{ __("content::admin.image") }}</th>
                <th>{{ __("content::admin.name") }}</th>
                <th>{{ __("content::admin.category") }}</th>
                <th>{{ __("content::admin.updated_at") }}</th>
                <th>{{ __("content::admin.actions") }}</th>
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
                ajax : "{{ route('admin.pages.contents.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'published', name: 'published' },
                    { data: 'image', name: 'image' },
                    { data: 'name', name: 'name' },
                    { data: 'content_category_id', name: 'content_category_id' },
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
