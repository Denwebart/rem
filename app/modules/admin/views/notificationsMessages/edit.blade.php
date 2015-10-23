@extends('admin::layouts.admin')

<?php
$title = 'Редактирование уведомления';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><i class="fa fa-edit "></i>
            {{ $title }}
            <small></small></h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>
            <li class="active"><a href="{{ URL::route('admin.notificationsMessages.index') }}">Шаблоны уведомлений</a></li>
            <li>{{ $title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $notificationMessage->description }}</h4>
            </div>
            {{ Form::model($notificationMessage, ['method' => 'PUT', 'route' => ['admin.notificationsMessages.update', $notificationMessage->id], 'id' => 'ruleForm']) }}
                @include('admin::notificationsMessages._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop