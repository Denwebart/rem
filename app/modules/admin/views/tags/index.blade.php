@extends('admin::layouts.admin')

<?php
$title = 'Теги';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-tags"></i>
                    {{ $title }}
                    <small>теги к статьям пользователей</small>
                </h1>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12">
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
            <div class="col-md-12">
                <a href="{{ URL::route('admin.tags.merge') }}" class="btn btn-primary">Объединение тегов</a>
            </div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-title">
                        <h3>Добавить тег</h3>
                    </div>
                    <div class="box-body row">
                        {{ Form::model($tag, ['method' => 'POST', 'route' => ['admin.tags.store'], 'id' => 'tagsForm', 'files' => true]) }}
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('image', 'Изображение') }}<br/>
                                    {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                                    {{ $errors->first('image') }}
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    {{ Form::label('title', 'Тег') }}
                                    {{ Form::text('title', $tag->title, ['class' => 'form-control', 'placeholder' => 'Новый тег']) }}
                                    {{ $errors->first('title') }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                {{ Form::submit('Сохранить', ['class' => 'btn btn-success margin-top-25']) }}
                            </div>
                            {{ Form::hidden('_token', csrf_token()) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div><!-- ./col -->

            <div class="col-xs-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div id="count" class="count">
                            @include('admin::parts.count', ['models' => $tags])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.tags.search'], 'id' => 'search-tags-form', 'class' => 'table-search']) }}
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            {{ Form::text('query', Request::has('query') ? Request::get('query') : null, [
                                'class' => 'form-control',
                                'id' => 'query',
                                'placeholder' => 'Введите тег'
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
                                        {{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id') }}
                                    </th>
                                    <th>
                                        {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Изображение', 'image') }}
                                    </th>
                                    <th>
                                        {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title') }}
                                    </th>
                                    <th>
                                        {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статьи по тегу', 'pages') }}
                                    </th>
                                    <th class="button-column"></th>
                                </tr>
                            </thead>
                            <tbody id="tags-list">
                                @include('admin::tags.list', ['tags' => $tags])
                            </tbody>
                        </table>
                        <div class="pull-left">
                            {{ SortingHelper::paginationLinks($tags) }}
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();

        $(".file-inputs").on("change", function(){
            var file = this.files[0];
            if (file.size > 5242880) {
                $(this).parent().parent().append('Недопустимый размер файла.');
            }
        });
    </script>

    <script type="text/javascript">
        $('button[name="destroy"]').on('click', function(e){
            var $form=$(this).closest('form');
            e.preventDefault();
            $('#confirm').modal({ backdrop: 'static', keyboard: false })
            .one('click', '#delete', function() {
                $form.trigger('submit'); // submit the form
            });
        });

        $('#query').keyup(function () {
            $("#search-pages-form").submit();
        });
        $("form[id^='search-tags-form']").submit(function(event) {
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
                        $('#tags-list').html(response.tagsListHtmL);
                        $('#pagination').html(response.tagsPaginationHtmL);
                        $('#count').html(response.tagsCountHtmL);
                    }
                },
            });
        });
    </script>
@stop