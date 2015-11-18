@extends('admin::layouts.admin')

<?php
$title = 'Создание рекламного блока или виджета';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-plus "></i>
            {{ $title }}
            <small>заполните все необходимые формы</small>
        </h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.advertising.index') }}">Реклама и виджеты</a></li>
            <li>{{ $title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <div class="buttons margin-bottom-10 margin-top-10 display-inline-block pull-right">
                    <a href="javascript:void(0)" class="btn btn-success pull-left margin-right-5 save-button">Сохранить</a>
                    <a href="{{ $backUrl }}" class="btn btn-primary pull-left">Отмена</a>
                </div>
            </div>

            {{ Form::model($advertising, ['method' => 'POST', 'route' => ['admin.advertising.store']], ['id' => 'advertisingForm']) }}
                @include('admin::advertising._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop