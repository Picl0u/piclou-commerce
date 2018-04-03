@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>{{ __("admin::navigation.sale") }}</span></li>
            <li><span>{{ __("coupon::admin.navigation") }}</span></li>
            <li><span>{{ __("admin::actions.add") }}</span></li>
        </ul>
    </nav>

    <h2>
        {{ __("admin::actions.add") }}
        <span> - {{ __("coupon::admin.add_title") }}</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.coupon.index") }}">
            <i class="fas fa-arrow-left"></i>
            {{ __("admin::actions.return") }}
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "admin.coupon.store"]) !!}
        @include('coupon::admin.form',compact('data','users','products'))
        <div class="form-buttons">
            {{ Form::submit(__("admin::actions.save")) }}
        </div>
    {!! Form::close() !!}
@endsection
