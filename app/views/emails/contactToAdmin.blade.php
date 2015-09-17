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
                <h3>{{ $subject }}</h3>
                <p>
                    {{ $message_text }}
                </p>
                <p>
                    Отправлено:
                    {{ DateHelper::dateFormat($created_at) }}
                </p>
                <p>
                    Отправитель:
                    @if(is_null($user_id))
                        {{ $user_name }}
                        ({{ $user_email }})
                    @else
                        <a href="{{ URL::route('user.profile', ['login' => $user_alias]) }}">
                            {{ $user_login }}
                        </a>
                        ({{ $user_email }})
                    @endif
                </p>
            </td>
        </tr>
    </table>
@stop