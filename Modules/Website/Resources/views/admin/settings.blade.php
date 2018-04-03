@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Configurer</span></li>
            <li><span>Paramètre du site</span></li>
            <li><span>Paramètres généraux</span></li>
        </ul>
    </nav>

    <h2>
        Paramètres généraux
        <span> - Gérez les paramètres pour votre site Internet</span>
    </h2>
@endsection

@section('content')

    {!! Form::open(['route' => "settings.generals.store", 'files' => true]) !!}

    <nav class="tabs" data-component="tabs">
        <ul>
            <li class="active"><a href="#infos">Informations</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="#invoice">Facture</a></li>
            <li><a href="#socials">Réseaux Sociaux</a></li>
            <li><a href="#seo">Référencement</a></li>
        </ul>
    </nav>

    <div id="infos">

        <div class="form-item">
            {{ Form::label('websiteName', 'Nom du site Internet') }}
            {{ Form::text('websiteName',$data['websiteName']) }}
        </div>

        <div class="form-item">
            {{ Form::label('logo', 'Logo') }}
            {{ Form::file('logo')  }}
            @if($data['logo'])
                <div class="image-form">
                    <img src="{{ resizeImage($data['logo'], 100, 100) }}"
                         alt="{{ $data['websiteName'] }}"
                         class="remodalImg"
                         data-src="{{ asset($data['logo']) }}"
                    >
                </div>
            @endif
        </div>

    </div>

    <div id="contact">

        <div class="row gutters">

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('firstname', 'Prénom') }}
                    {{ Form::text('firstname',$data['firstname']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('lastname', 'Nom') }}
                    {{ Form::text('lastname',$data['lastname']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('company', 'Société') }}
                    {{ Form::text('company',$data['company']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('siret', 'Numéro SIRET') }}
                    {{ Form::text('siret',$data['siret']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('email', 'Adresse email') }}
                    {{ Form::text('email',$data['email']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('phone', 'Téléphone') }}
                    {{ Form::text('phone',$data['phone']) }}
                </div>
            </div>

        </div>

        <div class="form-item">
            {{ Form::label('address', 'Adresse') }}
            {{ Form::text('address',$data['address']) }}
        </div>
        <div class="row gutters">

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('zipCode', 'Code postal') }}
                    {{ Form::text('zipCode',$data['zipCode']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('city', 'Ville') }}
                    {{ Form::text('city',$data['city']) }}
                </div>
            </div>

        </div>

    </div>

    <div id="invoice">

        <div class="row gutters">

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceLogo', 'Logo pour la facture') }}
                    {{ Form::file('invoiceLogo') }}
                    @if($data['invoiceLogo'])
                        <div class="image-form">
                            <img src="{{ resizeImage($data['invoiceLogo'], 100, 100) }}"
                                 alt="{{ $data['websiteName'] }}"
                                 class="remodalImg"
                                 data-src="{{ asset($data['invoiceLogo']) }}"
                            >
                        </div>
                    @endif
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceCompany', 'Société') }}
                    {{ Form::text('invoiceCompany',$data['invoiceCompany']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceSiret', 'Numéro SIRET') }}
                    {{ Form::text('invoiceSiret',$data['invoiceSiret']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceTVA', 'Numéro TVA') }}
                    {{ Form::text('invoiceTVA',$data['invoiceTVA']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceRCS', 'RCS') }}
                    {{ Form::text('invoiceRCS',$data['invoiceRCS']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoicePhone', 'Téléphone') }}
                    {{ Form::text('invoicePhone',$data['invoicePhone']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceNote', 'Ajouter une note') }}
                    {{ Form::textarea('invoiceNote',$data['invoiceNote']) }}
                    <div class="desc">Ajoute une note dans la facture</div>
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceFooter', 'Footer') }}
                    {{ Form::textarea('invoiceFooter',$data['invoiceFooter']) }}
                    <div class="desc">Texte en bas de la facture</div>
                </div>
            </div>

        </div>

        <div class="form-item">
            {{ Form::label('invoiceAddress', 'Adresse') }}
            {{ Form::text('invoiceAddress',$data['invoiceAddress']) }}
        </div>
        <div class="row gutters">

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceZipCode', 'Code postal') }}
                    {{ Form::text('invoiceZipCode',$data['invoiceZipCode']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceCity', 'Ville') }}
                    {{ Form::text('invoiceCity',$data['invoiceCity']) }}
                </div>
            </div>

            <div class="col col-6">
                <div class="form-item">
                    {{ Form::label('invoiceCountry', 'Pays') }}
                    {{ Form::text('invoiceCountry',$data['invoiceCountry']) }}
                </div>
            </div>

        </div>

    </div>

    <div id="socials">

        <div class="row gutters">

            <div class="col col-6 form-item">
                {{ Form::label('facebook', 'Page Facebook') }}
                {{ Form::text('facebook',$data['facebook']) }}
            </div>
            <div class="col col-6 form-item">
                {{ Form::label('twitter', 'Page Twitter') }}
                {{ Form::text('twitter',$data['twitter']) }}
            </div>
            <div class="col col-6 form-item">
                {{ Form::label('pinterest', 'Page Pinterest') }}
                {{ Form::text('pinterest',$data['pinterest']) }}
            </div>
            <div class="col col-6 form-item">
                {{ Form::label('googlePlus', 'Page Google Plus') }}
                {{ Form::text('googlePlus',$data['googlePlus']) }}
            </div>
            <div class="col col-6 form-item">
                {{ Form::label('instagram', 'Page Instagram') }}
                {{ Form::text('instagram',$data['instagram']) }}
            </div>
            <div class="col col-6 form-item">
                {{ Form::label('youtube', 'Chaine Youtube') }}
                {{ Form::text('youtube',$data['youtube']) }}
            </div>

        </div>

    </div>

    <div id="seo">
        <div class="form-item">
            <label class="checkbox">
                <?php
                $checked="";
                if(!empty($data['seoRobot'])) {
                    $checked = 'checked="checked"';
                }
                ?>
                <input type="checkbox" name="seoRobot" {{ $checked }}> Autoriser le référencement ?
            </label>
        </div>

        <div class="form-item">
            {{ Form::label('analytics', 'ID Google Analytics') }}
            {{ Form::text('analytics', $data['analytics']) }}
        </div>

        <div class="form-item">
            {{ Form::label('seoTitle', 'SEO Title') }}
            {{ Form::text('seoTitle', $data['seoTitle']) }}
        </div>

        <div class="form-item">
            {{ Form::label('seoDescription', 'SEO Description') }}
            {{ Form::textarea('seoDescription', $data['seoDescription']) }}
        </div>
    </div>

        <div class="form-buttons">
            {{ Form::submit('Enregistrer') }}
        </div>
    {!! Form::close() !!}
@endsection
