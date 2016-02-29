@extends('admin::layouts.admin')

<?php
$title = 'Отправленные письма';
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
        {{--<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">--}}
            {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
            {{--<li class="active">{{ $title }}</li>--}}
        {{--</ol>--}}
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="mailbox row">

            <div class="col-md-3 col-sm-4">
                @include('admin::letters.menu')
            </div>

            <div class="col-md-9 col-sm-8">
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

                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive scroll">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'ID', 'id') }}
                                            </th>
                                            {{--<th></th>--}}
                                            {{--<th></th>--}}
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Автор', 'user_id') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'IP', 'ip_id') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Тема', 'subject') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Получено', 'created_at') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Прочтено', 'read_at') }}
                                            </th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="letters-list">
                                            @include('admin::letters.list', ['letters' => $letters, 'notFoundLetters' => !count(Request::all()) ? 'Отправленных писем нет.' : 'Ничего не найдено.'])
                                        </tbody>
                                    </table>
                                    <div id="pagination" class="pull-left">
{{--                                        {{ SortingHelper::paginationLinks($letters) }}--}}
                                    </div>
                                </div><!-- /.table-responsive -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
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