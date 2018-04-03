@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>{{ __("admin::navigation.sale") }}</span></li>
            <li><span>{{ __("coupon::admin.navigation") }}</span></li>
        </ul>
    </nav>

   <h2>
       {{ __("coupon::admin.navigation") }}
      <span> - {{ __("coupon::admin.navigation_title") }}</span>
   </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.coupon.create") }}">
            <i class="fas fa-plus"></i>
            {{ __("admin::actions.add") }}
        </a>
        <div class="clear"></div>
    </div>
    <div class="datatable-container">
        <table class="datatable display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>{{ __("coupon::admin.id") }}</th>
                <th>{{ __("coupon::admin.name") }}</th>
                <th>{{ __("coupon::admin.coupon") }}</th>
                <th>{{ __("coupon::admin.reduce") }}</th>
                <th>{{ __("coupon::admin.updated_at") }}</th>
                <th>{{ __('admin::actions.action') }}</th>
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
                ajax : "{{ route('admin.coupon.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'coupon', name: 'coupon' },
                    { data: 'reduce', name: 'reduce' },
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
