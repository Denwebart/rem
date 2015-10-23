@extends('admin::layouts.admin')

<?php
$title = 'Реклама и виджеты';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-usd"></i>
                    {{ $title }}
                    <small>рекламные блоки и виджеты на сайте</small>
                </h1>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12">
                <div class="buttons">
                    <a class="btn btn-success btn-sm btn-full" href="{{ URL::route('admin.advertising.create', ['backUrl' => urlencode(Request::fullUrl())]) }}">
                        <i class="fa fa-plus "></i> Создать
                    </a>
                </div>
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
                            @include('admin::parts.count', ['models' => $advertising])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.advertising.search'], 'id' => 'search-widget-form', 'class' => 'table-search']) }}
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        {{ Form::select('area', ['' => '- Выберите область -'] + Advertising::$areas, Request::has('area') ? Request::get('area') : null, [
                            'id' => 'area',
                            'class' => 'form-control',
                            'placeholder' => 'Область',
                        ]) }}
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            {{ Form::text('query', Request::has('query') ? Request::get('query') : null, [
                                'class' => 'form-control',
                                'id' => 'query',
                                'placeholder' => 'Введите заголовок виджета'
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
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Тип', 'type') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Описание', 'description') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Область', 'area') }}</th>
                                <th>На страницах</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Позиция', 'position') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Доступ', 'access') }}</th>
                                <th class="status">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус', 'is_active') }}</th>
                                <th>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="widget-list">
                                @include('admin::advertising.list', ['advertising' => $advertising])
                            </tbody>
                        </table>
                        <div id="pagination" class="pull-left">
                            {{ SortingHelper::paginationLinks($advertising) }}
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

        $('.widget').on('click', '.change-active-status', function(){
            var $button = $(this),
                    isActive = $button.attr('data-is-active'),
                    advertisingId = $button.data('id');
            $.ajax({
                url: '/admin/advertising/changeActiveStatus/' + advertisingId,
                dataType: "text json",
                type: "POST",
                data: {is_active: isActive},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        if(response.isActive) {
                            $button.find('span').toggleClass('published not-published');
                            $button.attr('data-original-title', 'Включен. Выключить этот рекламный блок?');
                        } else {
                            $button.find('span').toggleClass('published not-published');
                            $button.attr('data-original-title', 'Выключен. Включить этот рекламный блок?');
                        }
                        $button.nextAll('.tooltip:first').remove();
                        $button.attr('data-is-active', response.isActive);
                    } else {
                        alert(response.message)
                    }
                }
            });
        });

        $('#area').on('change', function() {
            $("#search-widget-form").submit();
        });
        $('#query').keyup(function () {
            $("#search-widget-form").submit();
        });

        $("form[id^='search-widget-form']").submit(function(event) {
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
                        $('#widget-list').html(response.advertisingListHtmL);
                        $('#pagination').html(response.advertisingPaginationHtmL);
                        $('#count').html(response.advertisingCountHtmL);
                    }
                },
            });
        });
    </script>
@stop