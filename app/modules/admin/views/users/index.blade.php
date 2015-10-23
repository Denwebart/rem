@extends('admin::layouts.admin')

<?php
$title = 'Пользователи';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-users"></i>
                    {{ $title }}
                    <small>все пользователи сайта</small>
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
        <div class="row">

            <div class="col-xs-12 margin-bottom-15">
                <a href="{{ URL::route('admin.users.index') }}" class="btn btn-primary">
                    <span>Все пользователи</span>
                </a>
                <a href="{{ URL::route('admin.users.banned') }}" class="btn btn-dashed">
                    <span>Забаненные пользователи</span>
                </a>
                <a href="{{ URL::route('admin.ips.index') }}" class="btn btn-dashed">
                    <span>Все IP-адреса</span>
                </a>
                <a href="{{ URL::route('admin.ips.bannedIps') }}" class="btn btn-dashed">
                    <span>Забаненные IP-адреса</span>
                </a>
            </div>

            <div id="message"></div>

            <div class="col-xs-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div id="count" class="count">
                            @include('admin::parts.count', ['models' => $users])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.users.search'], 'id' => 'search-users-form', 'class' => 'table-search']) }}
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            {{ Form::text('query', Request::has('query') ? Request::get('query') : null, [
                                'class' => 'form-control',
                                'id' => 'query',
                                'placeholder' => 'Введите логин, имя или email'
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
                                    {{ SortingHelper::sortingLink('admin.users.index', 'ID', 'id') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Фото', 'avatar') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Роль', 'role') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Логин', 'login') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Имя', 'fullname') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Email', 'email') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Баллы', 'points') }}
                                </th>
                                <th class="status">
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Статус', 'is_active') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Зарегистрирован', 'created_at') }}
                                </th>
                                <th>
                                    Награды
                                </th>
                                <th>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="users-list">
                                @include('admin::users.list', ['users' => $users])
                            </tbody>
                        </table>
                        <div id="pagination" class="pull-left">
                            {{ SortingHelper::paginationLinks($users) }}
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
        // удаление пользователя
        $('button[name="destroy"]').on('click', function(e){
            var $form=$(this).closest('form');
            e.preventDefault();
            $('#confirm').modal({ backdrop: 'static', keyboard: false })
                    .one('click', '#delete', function() {
                        $form.trigger('submit'); // submit the form
                    });
        });

        // смена роли
        // показать кнопки после выбора роли
        $("form[id^='changeRole-form'] select").on('change', function(){
            $(this).parent().find('.buttons').html(
                '<button type="submit" class="btn btn-success btn-circle" name="changeRole"><i class="fa fa-check"></i></button>' +
                '<button type="button" class="btn btn-danger btn-circle" name="cancelChangeRole" data-role="' + $(this).data('role') + '"><i class="glyphicon glyphicon-remove"></i></button>'
            );
        });
        // отменить изменение роли
        $("form[id^='changeRole-form'] .buttons").on('click', 'button[name="cancelChangeRole"]', function() {
            $(this).parent().parent().find('select').val($(this).data('role'));
            $(this).parent().html('');
        });
        // сохранить новую роль
        $("form[id^='changeRole-form']").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    role = $form.find('select').val(),
                    url = $form.attr('action');
            $.ajax({
                url: url,
                dataType: "text json",
                type: "POST",
                data: {role: role},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $form.find('.buttons').html('');
                        $form.find("select option[value='0']").remove();
                    }
                }
            });
        });

        // забанить
        $('.button-column').on('click', '.ban', function(){
            var userId = $(this).data('id');

            $('[data-ban-modal-id='+ userId +']').modal({ backdrop: 'static', keyboard: false })
                .one('click', '.ban-confirm', function() {
                    var $form = $('[data-ban-form-id='+ $(this).data('id') +']'),
                        data = $form.serialize();
                    $.ajax({
                        url: '/admin/users/ban/' + userId,
                        dataType: "text json",
                        type: "POST",
                        data: {formData: data},
                        beforeSend: function(request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        },
                        success: function(response) {
                            if(response.success){
                                $('#message').text(response.message);
                                var $userTr = $('[data-user-id='+ userId +']');
                                $userTr.addClass('danger');
                                $userTr.find('.banned-link')
                                        .removeClass('ban').addClass('unban')
                                        .html('<i class="fa fa-unlock"></i>')
                                        .attr('data-original-title', 'Разбанить');
                                $form.find('#message').val('');
                            } else {
                                $('#message').text(response.message);
                            }
                        }
                    });
                });
        });

        // разбанить
        $('.button-column').on('click', '.unban', function(){
            var userId = $(this).data('id');

            $('[data-unban-modal-id='+ userId +']').modal({ backdrop: 'static', keyboard: false })
                .one('click', '.unban-confirm', function() {
                    $.ajax({
                        url: '/admin/users/unban/' + userId,
                        dataType: "text json",
                        type: "POST",
                        data: {},
                        beforeSend: function(request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        },
                        success: function(response) {
                            if(response.success){
                                $('#message').text(response.message);
                                var $userTr = $('[data-user-id='+ userId +']');
                                $userTr.removeClass('danger');
                                $userTr.find('.banned-link')
                                        .removeClass('unban').addClass('ban')
                                        .html('<i class="fa fa-lock"></i>')
                                        .attr('data-original-title', 'Забанить');
                            } else {
                                $('#message').text(response.message);
                            }
                        }
                    });
                });
        });

        $('#query').keyup(function () {
            $("#search-users-form").submit();
        });
        $("form[id^='search-users-form']").submit(function(event) {
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
                        $('#users-list').html(response.usersListHtmL);
                        $('#pagination').html(response.usersPaginationHtmL);
                        $('#count').html(response.usersCountHtmL);
                    }
                },
            });
        });
    </script>
@stop