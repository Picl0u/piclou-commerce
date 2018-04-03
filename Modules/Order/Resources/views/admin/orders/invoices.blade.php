@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Commandes</span></li>
            <li><span>Facture</span></li>
        </ul>
    </nav>

    <h2>
        Factures
        <span> - Exporter les factures de votre boutique</span>
    </h2>
@endsection

@section('content')

    {!! Form::open(['route' => "orders.invoices.export"]) !!}

        <div class="form-item">
            {{ Form::label('date_begin', 'Du') }}
            {{ Form::text('date_begin', null, ['class' => 'date-picker']) }}
        </div>

        <div class="form-item">
            {{ Form::label('date_end', 'Au') }}
            {{ Form::text('date_end', null, ['class' => 'date-picker']) }}
        </div>

        <div class="form-buttons">
            {{ Form::submit('Exporter') }}
        </div>
    {!! Form::close() !!}

    <div class="invoice-list">
        <ul>
            @foreach($exports as $export)
                <li>
                    Export du {{ Carbon\Carbon::parse($export->begin)->format('d/m/Y') }}
                    au {{ Carbon\Carbon::parse($export->end)->format('d/m/Y') }} -
                    Généré le : {{ $export->created_at->format('d/m/Y à H:i') }} -

                    <a href="{{ route("orders.invoices.download",['uuid' => $export->uuid]) }}" class="label focus">
                        Télécharger les factures
                    </a>

                </li>
            @endforeach
        </ul>
    </div>

@endsection

