@extends('layouts.email')

@section('content')
    <h2>Здравствуйте, {{ $user_name }}</h2>
    <p class="lead">
        Это копия сообщения, отправленного вами через контактную форму сайта
        <a href="{{ Config::get('app.url') }}">
            {{ Config::get('settings.siteUrl') }}
        </a>
        .
    </p>

    <table bgcolor="#ffffff">
        <tr>
            <td>
                <h3>{{ $subject }}</h3>
                <p>
                    {{ $message_text }}
                </p>
            </td>
        </tr>
    </table>
@stop