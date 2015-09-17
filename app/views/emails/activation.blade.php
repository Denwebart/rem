@extends('layouts.main')

@section('content')
    <h2>Спасибо за регистрацию.</h2>

    <p>Для подтверждения регистрации перейдите по ссылке {{ $activationUrl }}.</p>
@stop