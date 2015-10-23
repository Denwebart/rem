@extends('admin::layouts.admin')

<?php
$title = 'Создание правила';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-plus "></i>
            {{ $title }}
            <small></small>
        </h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>
            <li class="active"><a href="{{ URL::route('admin.advertising.index') }}">Правила сайта</a></li>
            <li>Создание правила</li>
        </ol>
    </div>
    <div class="content label-normal">
        <div class="row">
            {{ Form::model($rule, ['method' => 'POST', 'route' => ['admin.rules.store']], ['id' => 'ruleForm']) }}
                @include('admin::rules._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop