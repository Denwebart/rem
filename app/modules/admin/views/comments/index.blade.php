@extends('admin::layouts.admin')

<?php
$title = 'Комментарии';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-comment"></i>
                    {{ $title }}
                    <small>комментарии к статьям</small>
                </h1>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12">
            </div>
        </div>

        {{--<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">--}}
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
                            @include('admin::parts.count', ['models' => $comments])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.comments.search'], 'id' => 'search-comments-form', 'class' => 'table-search']) }}
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            {{ Form::text('author', Request::has('author') ? Request::get('author') : null, [
                                'class' => 'form-control',
                                'id' => 'author',
                                'placeholder' => 'Логин, имя пользователя или email'
                            ]) }}
                            <span class="input-group-btn">
                                <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        {{ Form::select('status', ['' => '- Статус публикации -'] + Comment::$status, Request::has('status') ? Request::get('status') : null, [
                            'id' => 'status',
                            'class' => 'form-control',
                            'placeholder' => 'Статус публикации',
                        ]) }}
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            {{ Form::text('query', Request::has('query') ? Request::get('query') : null, [
                                'class' => 'form-control',
                                'id' => 'query',
                                'placeholder' => 'Введите запрос'
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
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Автор', 'user_id') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'IP', 'ip_id') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Страница', 'page_id') }}</th>
                                <th max-width="30%">Комментарий</th>
                                <th class="status">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус', 'is_published') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Создан', 'created_at') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="comments-list">
                                @include('admin::comments.list', ['comments' => $comments])
                            </tbody>
                        </table>
                        <div id="pagination" class="pull-left">
                            {{ SortingHelper::paginationLinks($comments) }}
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

        $('#status').on('change', function() {
            $("#search-comments-form").submit();
        });
        $('#author, #query').keyup(function () {
            $("#search-comments-form").submit();
        });
        $("form[id^='search-comments-form']").submit(function(event) {
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
                        $('#comments-list').html(response.commentsListHtmL);
                        $('#pagination').html(response.commentsPaginationHtmL);
                        $('#count').html(response.commentsCountHtmL);
                    }
                },
            });
        });
    </script>
@stop