@extends('admin::layouts.admin')

<?php
$title = 'Правила сайта';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-exclamation-triangle"></i>
                    {{ $title }}
                    <small></small>
                </h1>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12">
                <div class="buttons">
                    <a class="btn btn-success btn-sm btn-full" href="{{ URL::route('admin.rules.create') }}">
                        <i class="fa fa-plus "></i> Создать
                    </a>
                </div>
            </div>
        </div>

        {{--<ol class="breadcrumb">--}}
            {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
            {{--<li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>--}}
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
                            @include('admin::parts.count', ['models' => $rules])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.rules.search'], 'id' => 'search-rules-form', 'class' => 'table-search']) }}
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
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
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Номер', 'position') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title') }}</th>
                                <th>Описание</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус', 'is_published') }}</th>
                                <th>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="rules-list">
                                @include('admin::rules.list', ['rules' => $rules])
                            </tbody>
                        </table>
                        <div id="pagination" class="pull-left">
                            {{ SortingHelper::paginationLinks($rules) }}
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

        $('#query').keyup(function () {
            $("#search-rules-form").submit();
        });
        $("form[id^='search-rules-form']").submit(function(event) {
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
                        $('#rules-list').html(response.rulesListHtmL);
                        $('#pagination').html(response.rulesPaginationHtmL);
                        $('#count').html(response.rulesCountHtmL);
                    }
                },
            });
        });
    </script>
@stop