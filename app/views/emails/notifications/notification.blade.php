@extends('layouts.main')

@section('content')
    <h2>
        Сообщение с сайта
        <a href="{{ Config::get('app.url') }}">
            {{ Config::get('settings.siteUrl') }}
        </a>
    </h2>

    <table bgcolor="#ffffff">
        <tr>
            <td>
                <p>
                    {{ $notificationMessage }}
                </p>
            </td>
        </tr>
    </table>
@stop