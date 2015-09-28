@extends('admin::layouts.admin')

<?php
$title = 'Статьи пользователей';
View::share('title', $title);

$params = isset($parentPage) ? ['id' => $parentPage->id] : [];
?>

@section('content')
<div class="page-head">
    <div class="row">
        <div class="col-md-10 col-sm-9 col-xs-12">
            <h1>
                <i class="fa fa-file-o"></i>
                {{ $title }}
                <small>статьи из бортового журнала</small>
            </h1>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-12">
            <div class="buttons">
                <a class="btn btn-success btn-sm btn-full" href="{{ URL::route('admin.articles.create') }}">
                    <i class="fa fa-plus "></i> Создать
                </a>
            </div>
        </div>
    </div>
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
        {{--<li class="active">{{ $title }}</li>--}}
    {{--</ol>--}}
</div>

<div class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div id="count" class="count">
                        @include('admin::parts.count', ['models' => $pages])
                    </div>
                </div>
                {{ Form::open(['method' => 'GET', 'route' => ['admin.articles.search'], 'id' => 'search-pages-form', 'class' => 'table-search']) }}
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="input-group">
                        {{ Form::text('author', Request::has('author') ? Request::get('author') : null, [
                            'class' => 'form-control',
                            'id' => 'author',
                            'placeholder' => 'Логин или имя пользователя'
                        ]) }}
                        <span class="input-group-btn">
                                <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="input-group">
                        {{ Form::text('query', Request::has('query') ? Request::get('query') : null, [
                            'class' => 'form-control',
                            'id' => 'query',
                            'placeholder' => 'Введите заголовок статьи'
                        ]) }}
                        <span class="input-group-btn">
                            <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
                {{ Form::close() }}
            </div>

            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Автор', 'user_id', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Изображение', 'image', $params) }}
                                </th>
                                <th width="30%">
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Категория', 'parent_id', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус публикации', 'is_published', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Дата создания', 'created_at', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Дата публикации', 'published_at', $params) }}
                                </th>
                                <th class="button-column">
                                </th>
                            </tr>
                        </thead>
                        <tbody id="pages-list">
                            @include('admin::articles.list', ['pages' => $pages])
                        </tbody>
                    </table>
                    <div id="pagination" class="pull-left">
                        {{ SortingHelper::paginationLinks($pages) }}
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</div>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        $('button[name="destroy"]').on('click', function(e){
            var $form=$(this).closest('form');
            e.preventDefault();
            $('#confirm').modal({ backdrop: 'static', keyboard: false })
            .one('click', '#delete', function() {
                $form.trigger('submit'); // submit the form
            });
        });

        $('#author, #query').keyup(function () {
            $("#search-pages-form").submit();
        });
        $("form[id^='search-pages-form']").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            $.ajax({
                url: url,
                type: "get",
                data: {searchData: data},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    //to change the browser URL to the given link location
                    window.history.pushState({parent: response.url}, '', response.url);

                    if(response.success) {
                        $('#pages-list').html(response.pagesListHtmL);
                        $('#pagination').html(response.pagesPaginationHtmL);
                        $('#count').html(response.pagesCountHtmL);
                    }
                },
            });
        });
    </script>
@stop