@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li>Мои фотографии</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ HTML::image(Config::get('settings.defaultAvatar'), $user->login, ['class' => 'img-responsive']) }}
            </div>
        </div>
        <div class="col-lg-9">
            <h2>Фото {{ $user->login }}</h2>

        </div>
    </div>
@stop