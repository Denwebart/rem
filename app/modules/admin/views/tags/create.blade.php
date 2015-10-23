@extends('admin::layouts.admin')

<?php
$title = 'Добавление тега';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-plus"></i>
            {{ $title }}
        </h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.tags.index') }}">Теги</a></li>
            <li>{{ $tag->title }}</li>
        </ol>
    </div>
    <div class="content label-normal">
        <div class="row">
            {{ Form::model($tag, ['method' => 'POST', 'route' => ['admin.tags.store'], 'id' => 'tagsForm', 'files' => true]) }}
                @include('admin::tags._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop