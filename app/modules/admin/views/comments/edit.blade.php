@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Редактирование комментария <small>модерация комментария</small></h1>
        <ol class="breadcrumb">
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
            {{ Form::close() }}
        </div>
    </div>
@stop