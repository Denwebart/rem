@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li>Мой автомобиль</li>
            </ol>
        </div>

        <div class="col-lg-3">

        </div>
        <div class="col-lg-9">
            <h2>Мой автомобиль</h2>

            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>

            <div id="new-photo">

                <h3>Добавить фотографию</h3>

                {{--<a href="" class="btn btn-default btn-lg">--}}
                    {{--<span class="glyphicon glyphicon-plus"></span>--}}
                {{--</a>--}}

                {{ Form::model($user, ['method' => 'POST', 'route' => ['user.gallery.uploadPhoto', $user->id], 'files' => true], ['id' => 'uploadPhoto']) }}



                {{ Form::close() }}

            </div>

        </div>
    </div>
@stop