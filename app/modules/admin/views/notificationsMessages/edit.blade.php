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
            <div class="col-md-8">
                <h4 class="no-margin-top">{{ $notificationMessage->description }}</h4>
            </div>
            <div class="col-md-4">
                <div class="buttons margin-bottom-10 margin-top-10 display-inline-block pull-right">
                    <a href="javascript:void(0)" class="btn btn-success save-button">Сохранить</a>
                    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
                </div>
            </div>
            {{ Form::model($notificationMessage, ['method' => 'PUT', 'route' => ['admin.notificationsMessages.update', $notificationMessage->id], 'id' => 'notificationsMessagesForm']) }}
                @include('admin::notificationsMessages._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop