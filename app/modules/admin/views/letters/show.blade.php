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
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
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
            <div class="col-md-3 col-sm-4">
                @include('admin::letters.menu')
            </div>
            <div class="col-md-9 col-sm-8">

                <div class="row">

                    <div class="col-md-12">
                        <h4 class="no-margin-top display-inline-block">
                            <span class="pull-left margin-right-10">Отправитель:</span>
                            @if($letter->user)
                                <a href="{{ URL::route('user.profile', ['login' => $letter->user->getLoginForUrl()]) }}" class="pull-left">
                                    {{ $letter->user->getAvatar('mini', ['width' => '25', 'class' => 'pull-left margin-right-10']) }}
                                    <span class="pull-left">{{ $letter->user->login }} ({{ $letter->user->email }})</span>
                                </a>
                            @else
                                <span>{{ $letter->user_name }} ({{ $letter->user_email }})</span>
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
        </div>
    </div>

@stop