@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Vendre</span></li>
            <li><span>Catalogue</span></li>
            <li><span>Produits</span></li>
            <li><span>Importer</span></li>
        </ul>
    </nav>

    <h2>
        Importer des déclinaisons
        <span> - Importer des déclinaisons de produits pour votre boutique</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("shop.products.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    {!! Form::open(['route' => "shop.products.imports.attributes.store", 'files' => true]) !!}

    <div class="form-item">
        {{ Form::label('file', "Fichier d'import") }}
        {{ Form::file('file')  }}
        <div class="desc">
            Votre fichier doit être un fichier .csv ou .xls ou .xlsx
        </div>
    </div>

    <div class="form-buttons">
        {{ Form::submit('Enregistrer') }}
    </div>
    {!! Form::close() !!}

    <hr>
    <h3>Vos derniers imports</h3>
    <div class="file-list">
        @if($files)
            <ul>
                @foreach($files as $file)
                    <li>
                        Votre fichier : <strong>{{ $file->getFilename () }}</strong> -
                        Importé le <strong>{{ date ("d/m/Y à H:i:s.", filemtime($file->getPathname())) }}</strong>
                        <a class="label focus" href="{{ asset($file->getPathname()) }}" target="_blank">
                            Télécharger
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Vous n'avez pas importé de déclinaisons</p>
        @endif
    </div>
@endsection
