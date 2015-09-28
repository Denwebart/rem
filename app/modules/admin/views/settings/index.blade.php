@extends('admin::layouts.admin')

<?php
$title = 'Настройки';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-cogs"></i>
                    {{ $title }}
                    <small>настройки сайта</small>
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

            <div class="col-xs-12">
                <a href="{{ URL::route('admin.rules.index') }}" class="btn btn-primary">
                    Правила сайта
                </a>
                <a href="{{ URL::route('admin.notificationsMessages.index') }}" class="btn btn-primary">
                    Шаблоны уведомлений
                </a>
            </div>

            <div class="col-xs-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div id="count" class="count">
                            @include('admin::parts.count', ['models' => $settings])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.settings.search'], 'id' => 'search-settings-form', 'class' => 'table-search']) }}
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
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Ключ', 'key') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Категория', 'key') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Тип', 'type') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Описание', 'description') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Значение', 'value') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус', 'is_published') }}</th>
                                <th class="button-column"></th>
                            </tr>
                            </thead>
                            <tbody id="settings-list">
                                @include('admin::settings.list', ['settings' => $settings])
                            </tbody>
                        </table>
                        <div id="pagination" class="pull-left">
                            {{ SortingHelper::paginationLinks($settings) }}
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
            $("#search-settings-form").submit();
        });
        $("form[id^='search-settings-form']").submit(function(event) {
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
                        $('#settings-list').html(response.settingsListHtmL);
                        $('#pagination').html(response.settingsPaginationHtmL);
                        $('#count').html(response.settingsCountHtmL);
                    }
                },
            });
        });
    </script>
@stop