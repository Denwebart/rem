@extends('admin::layouts.admin')

<?php
$title = 'Создание статьи';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-plus "></i>
            {{ $title }}
            <small>заполните все необходимые формы</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.articles.index') }}">Статьи пользователей</a></li>
            <li>{{ $title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            {{ Form::model($page, ['method' => 'POST', 'route' => ['admin.articles.store'], 'id' => 'articlesForm', 'files' => true]) }}
                @include('admin::articles._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop