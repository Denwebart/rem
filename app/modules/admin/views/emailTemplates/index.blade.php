@extends('admin::layouts.admin')

<?php
$title = 'Шаблоны email писем';
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
            </div>
        </div>

        {{--<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">--}}
            {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
            {{--<li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>--}}
            {{--<li class="active">{{ $title }}</li>--}}
        {{--</ol>--}}
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-xs-12 margin-bottom-15">
                <a href="{{ URL::route('admin.settings.index') }}" class="btn btn-dashed">
                    <span>Все настройки</span>
                </a>
                <a href="{{ URL::route('admin.rules.index') }}" class="btn btn-dashed">
                    <span>Правила сайта</span>
                </a>
                <a href="{{ URL::route('admin.notificationsMessages.index') }}" class="btn btn-dashed">
                    <span>Шаблоны уведомлений</span>
                </a>
                <a href="{{ URL::route('admin.emailTemplates.index') }}" class="btn btn-primary">
                    <span>Шаблоны email писем</span>
                </a>
                <a href="{{ URL::route('admin.menus.index') }}" class="btn btn-dashed">
                    <span>Меню сайта</span>
                </a>
            </div>

            <div class="col-xs-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div id="count" class="count">
                            @include('admin::parts.count', ['models' => $emailTemplates])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.emailTemplates.search'], 'id' => 'search-email-templates-form', 'class' => 'table-search']) }}
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
                                <th>Описание</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Тема', 'subject') }}</th>
                                <th>Текст письма</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="email-templates-list">
                                @include('admin::emailTemplates.list', ['emailTemplates' => $emailTemplates])
                            </tbody>
                        </table>
                        <div id="pagination" class="pull-left">
                            {{ SortingHelper::paginationLinks($emailTemplates) }}
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
        $('#query').keyup(function () {
            $("#search-email-templates-form").submit();
        });

        $("form[id^='search-email-templates-form']").submit(function(event) {
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
                        $('#email-templates-list').html(response.listHtmL);
                        $('#pagination').html(response.paginationHtmL);
                        $('#count').html(response.countHtmL);
                    }
                },
            });
        });
    </script>
@stop