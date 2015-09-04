@extends('admin::layouts.admin')

<?php
$title = 'Просмотр письма';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-search-plus "></i>
            {{ $title }}
            <small>содержимое письма</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.pages.index') }}">Письма</a></li>
            @if($letter->user)
                <li>Отправитель: {{ $letter->user->login }} ({{ $letter->user->email }})</li>
            @else
                <li>Отправитель: {{ $letter->user_name }} ({{ $letter->user_email }})</li>
            @endif
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="mailbox row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-title">
                        <i class="fa fa-inbox"></i>
                        <h3>Почтовый ящик</h3>
                        <div class="pull-right box-toolbar">
                            <a href="#" class="btn btn-link btn-xs"><i class="fa fa-cog"></i></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-4">
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active"><a href="{{ URL::route('admin.letters.index') }}"><i class="fa fa-inbox"></i> Входящие письма
                                            @if(count($headerWidget->newLetters()))
                                                <span class="label pull-right">
                                                    {{ count($headerWidget->newLetters()) }}
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                    {{--<li><a href="#"><i class="fa fa-envelope"></i> Отправленные письма</a></li>--}}
                                    <li><a href="{{ URL::route('admin.letters.trash') }}"><i class="fa fa-trash-o"></i> Удаленные письма
                                            @if(count($headerWidget->deletedLetters()))
                                                <span class="label label-danger pull-right">
                                                    {{ count($headerWidget->deletedLetters()) }}
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                    {{--<li><a href="#"><i class="fa fa-star"></i> Важные письма</a></li>--}}
                                </ul>

                                {{--<div class="mailbox-buttons">--}}
                                    {{--<div class="btn-group">--}}
                                        {{--<button type="button" class="btn btn-primary no-radius dropdown-toggle" data-toggle="dropdown">Выбрать действие <i class="fa fa-paper-plane"></i></button>--}}
                                        {{--<ul class="dropdown-menu">--}}
                                            {{--<li><a href="#">Отметить как прочитанное</a></li>--}}
                                            {{--<li><a href="#">Отметить как непрочитанное</a></li>--}}
                                            {{--<li><a href="#">Удалить</a></li>--}}
                                        {{--</ul>--}}
                                    {{--</div>--}}
                                    {{--<button type="button" class="btn btn-success no-radius"><i class="fa fa-plus"></i></button>--}}
                                    {{--<button type="button" class="btn btn-danger no-radius"><i class="fa fa-trash-o"></i></button>--}}
                                {{--</div>--}}

                                {{--<div class="box-bordered clearfix">--}}
                                    {{--<input type="text" class="form-control" placeholder="Тема" />--}}
                                    {{--<input type="text" class="form-control" placeholder="Email" />--}}
                                    {{--<textarea class="form-control" placeholder="Сообщение" rows="8"></textarea>--}}
                                    {{--<button type="submit" class="btn btn-danger no-radius pull-left">Отмена</button>--}}
                                    {{--<button type="submit" class="btn btn-success no-radius pull-right">Отправить</button>--}}
                                {{--</div>--}}
                            </div>
                            <div class="col-md-9 col-sm-8">

                                <div class="row">

                                    <div class="col-md-12">
                                        <h4 class="no-margin-top">
                                            @if($letter->user)
                                                Отправитель:
                                                <a href="{{ URL::route('user.profile', ['login' => $letter->user->getLoginForUrl()]) }}">
                                                    {{ $letter->user->getAvatar('mini', ['width' => '25px']) }}
                                                    {{ $letter->user->login }} ({{ $letter->user->email }})
                                                </a>
                                            @else
                                                Отправитель: {{ $letter->user_name }} ({{ $letter->user_email }})
                                            @endif
                                        </h4>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="box">
                                            <div class="box-title">
                                                <h3>{{ $letter->subject }}</h3>
                                            </div>
                                            <div class="box-body">
                                                <p>{{ $letter->message }}</p>
                                            </div>
                                        </div>
                                    </div><!-- ./col -->
                                    <div class="col-md-6">

                                    </div><!-- ./col -->
                                </div><!-- /.row -->

                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div>
                    <div class="box-footer">
                        {{--<div class="input-group">--}}
                            {{--<input class="form-control" placeholder="Поиск письма..."/>--}}
                            {{--<div class="input-group-btn">--}}
                                {{--<button class="btn btn-success"><i class="fa fa-search"></i></button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop