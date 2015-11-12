@extends('layouts.email')

@section('content')
    {{ EmailTemplate::getTemplate('changePassword', [
        'siteUrl' => Config::get('settings.siteUrl'),
        'resetUrl' => URL::to('password/reset', array($token)),
        'expireTime' => Config::get('auth.reminder.expire', 60),
    ]) }}
@stop