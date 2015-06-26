@extends('admin::layouts.admin')

<?php
$title = 'Редактирование правила №' . $rule->position;
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><i class="fa fa-edit "></i>
            {{ $title }}
            <small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>
            <li class="active"><a href="{{ URL::route('admin.advertising.index') }}">Правила сайта</a></li>
            <li>{{ $title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $rule->title }}</h4>
            </div>
            {{ Form::model($rule, ['method' => 'PUT', 'route' => ['admin.rules.update', $rule->id], 'id' => 'ruleForm']) }}
            @include('admin::rules._form')
            {{ Form::close() }}
        </div>
    </div>
@stop