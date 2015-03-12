@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li>Мои комментарии</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ HTML::image(Config::get('settings.defaultAvatar'), $user->login, ['class' => 'img-responsive avatar-default']) }}
            </div>
        </div>
        <div class="col-lg-9">
            <h2>Комментарии {{ $user->login }}</h2>

            <div id="comments" class="row">


            </div>
        </div>
    </div>
@stop