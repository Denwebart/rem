@extends('admin::layouts.admin')

<?php
$title = 'Награды';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-trophy"></i>
                    {{ $title }}
                    <small>комментарии к статьям</small>
                </h1>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12">
                <div class="buttons">
                    <a class="btn btn-success btn-sm btn-full" href="{{ URL::route('admin.honors.create', ['backUrl' => Session::has('user.url') ? urlencode(Session::get('user.url')) : urlencode(Request::fullUrl())]) }}">
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
                @if(Session::has('warningMessage'))
                    <p>{{ Session::get('warningMessage') }}</p>
                @endif
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div id="count" class="count">
                            @include('admin::parts.count', ['models' => $honors])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.honors.search'], 'id' => 'search-honors-form', 'class' => 'table-search']) }}
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
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Изображение', 'image') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Название', 'title') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Алиас', 'alias') }}</th>
                                <th>Описание</th>
                                <th>Пользователи</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="honors-list">
                                @include('admin::honors.list', ['honors' => $honors])
                            </tbody>
                        </table>
                        <div id="pagination" class="pull-left">
                            {{ SortingHelper::paginationLinks($honors) }}
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
                .one('click', '.delete', function() {
                    $form.trigger('submit'); // submit the form
                });
        });

        $('#query').keyup(function () {
            $("#search-honors-form").submit();
        });
        $("form[id^='search-honors-form']").submit(function(event) {
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
                        $('#honors-list').html(response.honorsListHtmL);
                        $('#pagination').html(response.honorsPaginationHtmL);
                        $('#count').html(response.honorsCountHtmL);
                    }
                },
            });
        });
    </script>
@stop