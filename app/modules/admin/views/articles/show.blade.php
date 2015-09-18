@extends('admin::layouts.admin')

<?php
$title = 'Просмотр статьи пользователя';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-search-plus "></i>
            {{ $title }}
            <small>информация о странице</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.pages.index') }}">Страницы</a></li>
            <li>{{ Str::limit($page->getTitle(), 60, '...') }}</li>
        </ol>
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-10">
                <h4 class="no-margin-top">{{ $page->getTitle() }}</h4>
            </div>
            <div class="col-md-2">
                <a class="btn btn-info btn-sm pull-right" href="{{ URL::route('admin.articles.edit', $page->id) }}">
                    <i class="fa fa-edit "></i> Редактировать
                </a>
            </div>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{ $page->id }}</td>
                            </tr>
                            <tr>
                                <td>Тип</td>
                                <td>{{ (!$page->is_container) ? Page::$types[$page->type] : 'Категория' }}</td>
                            </tr>
                            <tr>
                                <td>Пользователь</td>
                                <td>
                                    <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                                        {{ $page->user->getAvatar('mini', ['width' => '25px']) }}
                                        {{ $page->user->login }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Категория</td>
                                <td>
                                    @if($page->parent)
                                        @if($page->parent->parent)
                                            <a href="{{ URL::to($page->parent->parent->getUrl()) }}">
                                                {{ $page->parent->parent->getTitle() }}
                                            </a>
                                            /
                                        @endif
                                        <a href="{{ URL::to($page->parent->getUrl()) }}">
                                            {{ $page->parent->getTitle() }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>URL</td>
                                <td>
                                    <a href="{{ URL::to($page->getUrl()) }}">
                                        {{ URL::to($page->getUrl()) }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Заголовок</td>
                                <td>{{ $page->title }}</td>
                            </tr>
                            <tr>
                                <td>Статус публикации</td>
                                <td>
                                    @if($page->is_published)
                                        <span class="label label-success">Опубликован</span>
                                    @else
                                        <span class="label label-warning">Не опубликован</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Изображение</td>
                                <td>{{ $page->getImage('mini', ['width' => '100px']) }}</td>
                            </tr>
                            <tr>
                                <td>Альт к изображению</td>
                                <td>{{ $page->image_alt }}</td>
                            </tr>
                            <tr>
                                <td>Текст</td>
                                <td>{{ $page->content }}</td>
                            </tr>
                            <tr>
                                <td>Мета-тег Title</td>
                                <td>{{ $page->meta_title }}</td>
                            </tr>
                            <tr>
                                <td>Мета-тег Description</td>
                                <td>{{ $page->meta_desc }}</td>
                            </tr>
                            <tr>
                                <td>Мета-тег Keywords</td>
                                <td>{{ $page->meta_key }}</td>
                            </tr>
                            <tr>
                                <td>Дата создания</td>
                                <td>{{ $page->created_at }}</td>
                            </tr>
                            <tr>
                                <td>Дата обновления</td>
                                <td>{{ $page->updated_at }}</td>
                            </tr>
                            <tr>
                                <td>Дата публикации</td>
                                <td>{{ $page->published_at }}</td>
                            </tr>
                            <tr>
                                <td>Оценка</td>
                                <td>{{ $page->getRating() }} (Голосовавших: {{ $page->voters }})</td>
                            </tr>
                            <tr>
                                <td>Просмотры</td>
                                <td>{{ $page->views }}</td>
                            </tr>
                            <tr>
                                <td>Похожие статьи</td>
                                <td>
                                    <ul>
                                        @foreach($page->relatedArticles as $articles)
                                            <li data-id="{{ $articles->id }}">
                                                <a href="{{ URL::to($articles->getUrl()) }}" target="_blank">
                                                    {{ $articles->getTitle() }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>Похожие вопросы</td>
                                <td>
                                    <ul>
                                        @foreach($page->relatedQuestions as $question)
                                            <li data-id="{{ $question->id }}">
                                                <a href="{{ URL::to($question->getUrl()) }}" target="_blank">
                                                    {{ $question->getTitle() }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </div>
@stop
