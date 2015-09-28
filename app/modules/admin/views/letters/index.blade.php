@extends('admin::layouts.admin')

<?php
$title = 'Письма';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-envelope"></i>
                    {{ $title }}
                    <small>отправленные через контактную форму</small>
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
        <div class="mailbox row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-title">
                        <i class="fa fa-inbox"></i>
                        <h3>Почтовый ящик</h3>
                        <div class="pull-right box-toolbar">
                            <a href="#" class="btn btn-link btn-xs"><i class="fa fa-cog"></i></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-4">
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <a href="{{ URL::route('admin.letters.index') }}"><i class="fa fa-inbox"></i> Входящие письма
                                            @if($newLetters = count($headerWidget->newLetters()))
                                                <span class="label label-success pull-right">
                                                    {{ $newLetters }}
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                    {{--<li><a href="#"><i class="fa fa-envelope"></i> Отправленные письма</a></li>--}}
                                    <li>
                                        <a href="{{ URL::route('admin.letters.trash') }}"><i class="fa fa-trash-o"></i> Удаленные письма
                                            @if($deletedLetters = count($headerWidget->deletedLetters()))
                                                <span class="label label-danger pull-right">
                                                    {{ $deletedLetters }}
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                    {{--<li><a href="#"><i class="fa fa-star"></i> Важные письма</a></li>--}}
                                </ul>

                                {{--<div class="mailbox-buttons">--}}
                                    {{--<div class="btn-group">--}}
                                        {{--<button type="button" class="btn btn-primary no-radius dropdown-toggle" data-toggle="dropdown">Выбрать действие <i class="fa fa-paper-plane"></i></button>--}}
                                        {{--<ul class="dropdown-menu">--}}
                                            {{--<li><a href="#">Отметить как прочитанное</a></li>--}}
                                            {{--<li><a href="#">Отметить как непрочитанное</a></li>--}}
                                            {{--<li><a href="#">Удалить</a></li>--}}
                                        {{--</ul>--}}
                                    {{--</div>--}}
                                    {{--<button type="button" class="btn btn-success no-radius"><i class="fa fa-plus"></i></button>--}}
                                    {{--<button type="button" class="btn btn-danger no-radius"><i class="fa fa-trash-o"></i></button>--}}
                                {{--</div>--}}

                                {{--<div class="box-bordered clearfix">--}}
                                    {{--<input type="text" class="form-control" placeholder="Тема" />--}}
                                    {{--<input type="text" class="form-control" placeholder="Email" />--}}
                                    {{--<textarea class="form-control" placeholder="Сообщение" rows="8"></textarea>--}}
                                    {{--<button type="submit" class="btn btn-danger no-radius pull-left">Отмена</button>--}}
                                    {{--<button type="submit" class="btn btn-success no-radius pull-right">Отправить</button>--}}
                                {{--</div>--}}
                            </div>
                            <div class="col-md-9 col-sm-8">

                                {{--<div class="mailbox-tools clearfix">--}}
                                    {{--<div class="pull-left">--}}
                                        {{--<div class="btn-group">--}}
                                            {{--<button type="button" class="btn btn-info no-radius dropdown-toggle" data-toggle="dropdown">Выбрать действие <i class="fa fa-paper-plane"></i></button>--}}
                                            {{--<ul class="dropdown-menu">--}}
                                                {{--<li><a href="#">Отметить как прочитанное</a></li>--}}
                                                {{--<li><a href="#">Отметить как непрочитанное</a></li>--}}
                                                {{--<li><a href="#">Удалить</a></li>--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                        {{--<button type="button" class="btn btn-success no-radius"><i class="fa fa-plus"></i></button>--}}
                                        {{--<button type="button" class="btn btn-danger no-radius"><i class="fa fa-trash-o"></i></button>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="table-responsive scroll">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div id="count" class="count">
                                                @include('admin::parts.count', ['models' => $letters])
                                            </div>
                                        </div>
                                        {{ Form::open(['method' => 'GET', 'route' => ['admin.letters.search'], 'id' => 'search-letters-form', 'class' => 'table-search']) }}
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'ID', 'id') }}
                                            </th>
                                            {{--<th></th>--}}
                                            {{--<th></th>--}}
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Тема', 'subject') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Автор', 'user_id') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'IP', 'ip_id') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Дата создания', 'created_at') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Дата прочтения', 'read_at') }}
                                            </th>
                                            <th class="button-column"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="letters-list">
                                            @include('admin::letters.list', ['letters' => $letters, 'notFoundLetters' => !count(Request::all()) ? 'Входящих писем нет.' : 'Ничего не найдено.'])
                                        </tbody>
                                    </table>
                                    <div id="pagination" class="pull-left">
                                        {{ SortingHelper::paginationLinks($letters) }}
                                    </div>
                                </div><!-- /.table-responsive -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div>
                    <div class="box-footer">
                        {{--<div class="input-group">--}}
                            {{--<input class="form-control" placeholder="Поиск письма..."/>--}}
                            {{--<div class="input-group-btn">--}}
                                {{--<button class="btn btn-success"><i class="fa fa-search"></i></button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <!-- Confirm for delete -->
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
            $("#search-letters-form").submit();
        });
        $("form[id^='search-letters-form']").submit(function(event) {
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
                        $('#letters-list').html(response.lettersListHtmL);
                        $('#pagination').html(response.lettersPaginationHtmL);
                        $('#count').html(response.lettersCountHtmL);
                    }
                },
            });
        });
    </script>

    <!-- Inbox -->
    <script type="text/javascript">
        $(function() {
            //iCheck
            $("input[type='checkbox'], input[type='radio']").iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });

            // box scroll
            $('.scroll').slimScroll({
                height: '600px'
            });
        });
    </script>

@stop