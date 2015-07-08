@extends('admin::layouts.admin')

<?php
$title = 'Редактирование тега';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><h1><i class="fa fa-edit "></i>
                {{ $title }}
                <small>редактирование тега</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.tags.index') }}">Теги</a></li>
            <li>{{ $tag->title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $tag->title }}</h4>
            </div>
            {{ Form::model($tag, ['method' => 'PUT', 'route' => ['admin.tags.update', $tag->id], 'id' => 'tagsForm', 'files' => true]) }}
                @include('admin::tags._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop