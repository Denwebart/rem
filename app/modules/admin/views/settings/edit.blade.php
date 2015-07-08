@extends('admin::layouts.admin')

<?php
$title = 'Редактирование настроек';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><h1><i class="fa fa-edit "></i>
                {{ $title }}
                <small>редактирование настроек</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>
            <li>Редактирование {{ $setting->key }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $setting->title }}</h4>
            </div>
            {{ Form::model($setting, ['method' => 'PUT', 'route' => ['admin.settings.update', $setting->id], 'id' => 'settingsForm']) }}
                @include('admin::settings._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop