@extends("layouts.admin")

@section('title')
    <nav class="breadcrumbs">
        <ul>
            <li><span>Personnaliser</span></li>
            <li><span>Newsletter</span></li>
            <li><span>Contenu</span></li>
            <li><span>Modifier : {{ $data->name }}</span></li>
        </ul>
    </nav>

    <h2>
        Modifier le contenu : {{ $data->name }}
        <span> - Modifier ce contenu pour votre newsletter</span>
    </h2>
@endsection

@section('content')
    <div class="button-actions">
        <a href="{{ route("admin.newsletter.content.index") }}">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        <div class="clear"></div>
    </div>

    <div class="translate-actions">
        {!! formTranslate(\Modules\Newsletter\Entities\NewsletterContents::class, $data)
        ->action('admin.newsletter.content.translate')
        ->render() !!}
    </div>

    {!! Form::open(['route' => ["admin.newsletter.content.update", $data->uuid], 'files' => true]) !!}
        @include('newsletter::admin.content.form',compact('data'))
        <div class="form-buttons">
            {{ Form::submit('Editer') }}
        </div>
    {!! Form::close() !!}
@endsection
