@extends('admin::layouts.admin')

<?php
$title = 'Редактирование статьи пользователя';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><i class="fa fa-edit "></i>
            {{ $title }}
            <small>редактирование статьи из бортового журнала</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.articles.index') }}">Статьи пользователей</a></li>
            <li>{{ Str::limit($page->getTitle(), 60, '...') }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $page->getTitle() }}</h4>
            </div>
            {{ Form::model($page, ['method' => 'PUT', 'route' => ['admin.articles.update', $page->id], 'id' => 'registerForm']) }}
                @include('admin::articles._form')
            {{ Form::close() }}
        </div>
    </div>
@stop