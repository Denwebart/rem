@extends('admin::layouts.admin')

<?php
$title = 'Редактирование комментария';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-edit "></i>
            {{ $title }}
            <small>модерация комментария</small>
        </h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.comments.index') }}">Комментарии</a></li>
            <li>Комментарий к странице {{ Str::limit($comment->page->getTitle(), 50, '...') }}</li>
        </ol>
    </div>
    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">Комментарий к странице {{ Str::limit($comment->page->getTitle(), 50, '...') }}</h4>
            </div>
            {{ Form::model($comment, ['method' => 'PUT', 'route' => ['admin.comments.update', $comment->id], 'id' => 'registerForm']) }}
                @include('admin::comments._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop