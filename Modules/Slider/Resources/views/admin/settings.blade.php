@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Configurer</span></li>
            <li><span>Paramètre du site</span></li>
            <li><span>Slider</span></li>
        </ul>
    </nav>

    <h2>
        Slider
        <span> - Gérez les paramètres pour slider</span>
    </h2>
@endsection

@section('content')

    {!! Form::open(['route' => "settings.slider.store"]) !!}

    <nav class="tabs" data-component="tabs">
        <ul>
            <li class="active"><a href="#infos">Informations</a></li>
        </ul>
    </nav>

    <div id="infos">
        <div class="row gutters">
            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data['arrows'])) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="arrows" {{ $checked }}> Afficher les flêches ?
                </label>
            </div>
            <div class="col col-6 form-item">
                <label class="checkbox">
                    <?php
                    $checked="";
                    if(!empty($data['dots'])) {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" name="dots" {{ $checked }}> Afficher la pagination ?
                </label>
            </div>

            <div class="col col-6 form-item">
                {{ Form::label('type', 'Type de slider') }}
                {{  Form::select(
                    'type',
                    ['boxed' => 'Boxed', 'fullwidth' => 'FullWidth', 'fullscreen' => 'FullScreen'],
                    $data['type']
                ) }}
            </div>

            <div class="col col-6 form-item">
                {{ Form::label('transition', 'Type de transition') }}
                {{  Form::select(
                    'transition',
                    ['fade' => 'Effet en fondu', 'slide' => 'Effet en glissé'],
                    $data['transition']
                ) }}
            </div>

            <div class="col col-6 form-item">
                {{ Form::label('slideDuration', 'Durée entre les slides') }}
                {{ Form::text('slideDuration',$data['slideDuration']) }}
                <div class="desc">En milliseconde, exemple : 1s = 1000 ms</div>
            </div>

            <div class="col col-6 form-item">
                {{ Form::label('transitionDuration', 'Durée de l\'effet d\'apparition') }}
                {{ Form::text('transitionDuration',$data['transitionDuration']) }}
                <div class="desc">En milliseconde, exemple : 1s = 1000 ms</div>
            </div>

        </div>

    </div>


    <div class="form-buttons">
        {{ Form::submit('Enregistrer') }}
    </div>
    {!! Form::close() !!}
@endsection
