@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li>Личные сообщения</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <h3>Люди</h3>



        </div>
        <div class="col-lg-9">
            <h2>Личные сообщения</h2>

            <div id="messages" class="row">

                <div class="col-md-1">
                    {{--<img src="/images/default-avatar.jpg" alt=""/>--}}
                </div>
                <div class="col-md-6">

                </div>

            </div>

        </div>
    </div>
@stop