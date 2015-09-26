@extends('admin::layouts.admin')

<?php
$params = $parentPage ? ['parent_id' => $parentPage->id] : [];
?>

@section('content')
<div class="page-head">
    <div class="row">
        <div class="col-md-10 col-sm-9 col-xs-12">
            <h1>
                @include('admin::pages.title', ['parentPage' => $parentPage])
            </h1>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-12">
            <div class="buttons">
                <a class="btn btn-success btn-sm btn-full" href="{{ URL::route('admin.pages.create') }}">
                    <i class="fa fa-plus "></i> Создать
                </a>
            </div>
        </div>
    </div>
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
        {{--@if(isset($parentPage))--}}
            {{--<li class="active">--}}
                {{--<a href="{{ URL::route('admin.pages.index') }}">--}}
                    {{--Страницы--}}
                {{--</a>--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--Подпункты страницы "{{ $parentPage->getTitle() }}"--}}
            {{--</li>--}}
        {{--@else--}}
            {{--<li class="active">Страницы</li>--}}
        {{--@endif--}}
    {{--</ol>--}}
</div>

<div class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div id="count" class="count">
                        @include('admin::pages.count', ['pages' => $pages])
                    </div>
                </div>
                {{ Form::open(['method' => 'GET', 'route' => ['admin.pages.search'], 'id' => 'search-pages-form', 'class' => 'table-search']) }}
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
                        {{ Form::select('parent_id', ['0' => '- Выберите категорию -'] + Page::getContainer(true, false), Request::has('parent_id') ? Request::get('parent_id') : null, [
                            'id' => 'category',
                            'class' => 'form-control',
                            'placeholder' => 'Категория',
                        ]) }}
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
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Тип', 'is_container', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Изобр.', 'image', $params) }}
                                </th>
                                <th width="30%">
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Категория', 'parent_id', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус', 'is_published', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Cоздана', 'created_at', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Опубликована', 'published_at', $params) }}
                                </th>
                                <th class="">
                                </th>
                            </tr>
                        </thead>
                        <tbody id="pages-list">
                            @include('admin::pages.list', ['pages' => $pages])
                        </tbody>
                    </table>
                    <div id="pagination" class="pull-left">
                        @include('admin::pages.pagination', ['pages' => $pages])
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

        $('#category').on('change', function() {
            $("#search-pages-form").submit();
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
                        $('h1').html(response.pagesTitleHtmL);
                    }
                },
            });
        });
    </script>
@stop