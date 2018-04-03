@extends('layouts.mail')

@section('message')
    <table style="width:100%"  cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align:center; text-transform:uppercase;font-size:20px;">
                Commentaire sur le produit {{ $product->ref }} - {{ $product->name }}
            </td>
        </tr>
        <tr><td style="height:40px;"></td></tr>
        <tr>
            <td>
                Bonjour, <br>
                Un nouveau commentaire a été posté sur votre site Internet. Le commentaire concerne le produit :
                <a href="{{ route('product.show',['slug' => $product->slug, 'id' => $product->id]) }}">
                    {{ $product->name }}
                </a>.<br>
                Voici le détail du commentaire.
            </td>
        </tr>
        <tr><td style="height:20px;"></td></tr>
        <tr>
            <td style="font-size:12px;">
                <ul>
                    <li>
                        <strong>Produit : </strong> {{ $product->name }}
                    </li>
                    <li>
                        <strong>Lien du produit  : </strong>
                        <a href="{{ route('product.show',['slug' => $product->slug, 'id' => $product->id]) }}">
                            Cliquez ici
                        </a>
                    </li>
                    <li>
                        <strong>Utilisateur : </strong> {{ $user->firstname }} {{ $user->lastname }}
                    </li>
                    <li>
                        <strong>Email : </strong>
                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </li>
                    <li>
                        <strong>Commentaire : </strong><br>
                        {!! nl2br($comment) !!}
                    </li>
                </ul>
            </td>
        </tr>

        <tr><td style="height:40px;"></td></tr>
        <tr>
            <td>
                Vous pouvez modifier / supprimer ce commentaire depuis votre back office.
            </td>
        </tr>
        <tr><td style="height:20px;"></td></tr>

    </table>
@endsection