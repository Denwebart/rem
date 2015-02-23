@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="avatar">
                {{ HTML::image(Config::get('settings.defaultAvatar'), $user->login, ['class' => 'img-responsive']) }}
            </div>
        </div>
        <div class="col-lg-8">
            <h1>Вопросы {{ $user->login }}</h1>

        </div>
    </div>
@stop