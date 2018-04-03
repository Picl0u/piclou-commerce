@extends('layouts.mail')

@section('message')
    <table style="width:100%"  cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align:center; text-transform:uppercase;font-size:20px;">
                Contact
            </td>
        </tr>
        <tr><td style="height:40px;"></td></tr>
        <tr>
            <td>
                Bonjour,<br>
                une personne vous a contacté depuis votre site Internet.<br>
                Voici le récapitulatif :

            </td>
        </tr>
        <tr><td style="height:20px;"></td></tr>
        <tr><td style="height:1px; background-color:#CCC"></td></tr>
        <tr><td style="height:40px;"></td></tr>
        <tr>
            <td style="">
                <strong>Nom : </strong> {{ $contact['lastname'] }}<br>
                <strong>Prénom : </strong> {{ $contact['firstname'] }}<br>
                <strong>Email : </strong> <a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a><br>
                <strong>Message : </strong><br> {!! nl2br($contact['message']) !!}
            </td>
        </tr>
        <tr><td style="height:40px;"></td></tr>
    </table>
@endsection