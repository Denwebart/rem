@extends('admin::layouts.admin')

<?php
$title = 'Забаненные пользователи';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-ban"></i>
                    <i class="fa fa-users"></i>
                    {{ $title }}
                    <small>забаненные пользователи сайта</small>
                </h1>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12">
            </div>
        </div>

        {{--<ol class="breadcrumb">--}}
            {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
            {{--<li class="active"><a href="{{ URL::route('admin.users.index') }}">Пользователи</a></li>--}}
            {{--<li class="active">{{ $title }}</li>--}}
        {{--</ol>--}}
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-xs-12">
                <a href="{{ URL::route('admin.users.index') }}" class="btn btn-primary">
                    Все пользователи
                </a>
                <a href="{{ URL::route('admin.users.bannedUsers') }}" class="btn btn-primary btn-outline">
                    Забаненные пользователи
                </a>
                <a href="{{ URL::route('admin.users.ips') }}" class="btn btn-primary">
                    Все IP-адреса
                </a>
                <a href="{{ URL::route('admin.users.bannedIps') }}" class="btn btn-primary">
                    Забаненные IP-адреса
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
                                    {{ SortingHelper::sortingLink('admin.users.bannedUsers', 'ID', 'id') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.bannedUsers', 'Фото', 'avatar') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.bannedUsers', 'Роль', 'role') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.bannedUsers', 'Логин', 'login') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.bannedUsers', 'Имя', 'fullname') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.bannedUsers', 'Email', 'email') }}
                                </th>
                                <th>
                                    Сколько раз забанен
{{--                                    {{ SortingHelper::sortingLink('admin.users.bannedUsers', 'Сколько раз забанен', '') }}--}}
                                </th>
                                <th>
                                    Забанен
{{--                                    {{ SortingHelper::sortingLink('admin.users.bannedUsers', 'Забанен', '') }}--}}
                                </th>
                                <th>
                                    Разбанен
{{--                                    {{ SortingHelper::sortingLink('admin.users.bannedUsers', 'Разбанен', '') }}--}}
                                </th>
                                <th>
                                    Причина бана
                                </th>
                                <th>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="users-list">
                                @include('admin::users.bannedUsersList', ['users' => $users])
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

        // разбанить
        $('.buttons').on('click', '.unban', function(){
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
                                $('[data-user-id='+ userId +']').remove();
                                $('#message').text(response.message);
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
                data: {searchData: data, view: 'bannedUsersList', route: 'banned'},
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