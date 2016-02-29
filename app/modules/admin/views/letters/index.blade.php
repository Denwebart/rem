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
        {{--<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">--}}
            {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
            {{--<li class="active">{{ $title }}</li>--}}
        {{--</ol>--}}
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="mailbox row">

            <div class="col-lg-12 col-md-12 col-sm-12">
                @include('admin::letters.menu')
            </div>

            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-12">
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
                    </div>
                    <div class="col-lg-4 col-md-5 col-sm-5">
                        <div class="box">
                            <div class="box-body">
                                <div class="scroll">
                                    <div id="letters-list">
                                        @include('admin::letters.list', ['letters' => $letters, 'notFoundLetters' => !count(Request::all()) ? 'Входящих писем нет.' : 'Ничего не найдено.'])
                                    </div>
                                    <div id="pagination" class="pull-left">
                                        {{ SortingHelper::paginationLinks($letters) }}
                                    </div>
                                </div><!-- /.table-responsive -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-sm-7">
                        Письмо
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
            var windowHeight = $(window).height();
            var el = $('.scroll');
            var offset = el.offset();
            var startOfSection = offset.top;
            console.log((windowHeight - startOfSection));
            $('.scroll').slimScroll({
                height: (windowHeight - startOfSection - 40) + 'px'
            });
        });
    </script>

@stop