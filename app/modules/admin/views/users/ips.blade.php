@extends('admin::layouts.admin')

<?php
$title = 'Все IP-адреса';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-laptop"></i>
            {{ $title }}
            <small>все IP-адреса</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.users.index') }}">Пользователи</a></li>
            <li class="active">{{ $title }}</li>
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-xs-12">
                <a href="{{ URL::route('admin.users.index') }}" class="btn btn-primary">
                    Все пользователи
                </a>
                <a href="{{ URL::route('admin.users.bannedUsers') }}" class="btn btn-primary">
                    Забаненные пользователи
                </a>
                <a href="{{ URL::route('admin.users.ips') }}" class="btn btn-primary btn-outline">
                    Все IP-адреса
                </a>
                <a href="{{ URL::route('admin.users.bannedIps') }}" class="btn btn-primary">
                    Забаненные IP-адреса
                </a>
            </div>

            <div id="message"></div>

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped" id="banned-ips-table">
                            <thead>
                            <tr>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.ips', 'IP', 'ip') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.ips', 'Пользователи', 'users') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.ips', 'Комментарии', 'comments') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.ips', 'Письма', 'letters') }}
                                </th>
                                <th class="button-column"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ips as $ip)
                                <tr data-ip-id="{{ $ip->id }}" @if($ip->is_banned) class="danger" @endif>
                                    <td>{{ $ip->ip }}</td>
                                    <td>
                                        <p>Пользователи: {{ count($ip->users) }}</p>
                                        @foreach($ip->users as $key => $user)
                                            <div class="user">
                                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                                    {{ $user->getAvatar('mini', ['width' => '25px']) }}
                                                    <span>{{ $user->login }}</span>
                                                </a>
                                                {{ (count($ip->users) - 1 > $key) ? "," : "" }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ count($ip->comments) }}</td>
                                    <td>{{ count($ip->letters) }}</td>
                                    <td class="buttons">
                                        <!-- Бан ip-адреса -->
                                        @if(Request::ip() != $ip->ip)
                                            @if(!$ip->is_banned)
                                                <a class="btn btn-primary btn-sm banned-link ban" href="javascript:void(0)" title="Забанить" data-id="{{ $ip->id }}">
                                                    <i class="fa fa-lock"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-primary btn-sm banned-link unban" href="javascript:void(0)" title="Разбанить" data-id="{{ $ip->id }}">
                                                    <i class="fa fa-unlock"></i>
                                                </a>
                                            @endif
                                        @endif

                                        <div class="modal fade unban-modal" data-unban-modal-id="{{ $ip->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Снятие бана с ip-адреса</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Вы уверены, что хотите разбанить ip-адрес {{ $ip->ip }}?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-success unban-confirm" data-dismiss="modal" data-id="{{ $ip->id }}">Разбанить</button>
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->

                                        <div class="modal fade ban-modal" data-ban-modal-id="{{ $ip->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Бан ip-адреса</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Вы уверены, что хотите забанить ip-адрес {{ $ip->ip }}?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-success ban-confirm" data-dismiss="modal" data-id="{{ $ip->id }}">Забанить</button>
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-left">
                            {{ SortingHelper::paginationLinks($ips) }}
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </div>
@stop

@section('style')
    @parent
    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
    <script src="/js/jquery-ui.min.js"></script>
@stop

@section('script')
    @parent

    <script type="text/javascript">

        $("#ip").autocomplete({
            source: "<?php echo URL::route('admin.users.ipsAutocomplete') ?>",
            minLength: 2,
            select: function(e, ui) {
                $(this).val(ui.item.value);
                $("#ban-ip-form").find('.error').empty();
                $("#ban-ip-form").find('.error').parent().removeClass('has-error');
            }
        });

        $("#ip").on('change', function() {
            $form.find('.error').empty();
            $form.find('.error').parent().removeClass('has-error');
        });

        // забанить
        $('.buttons').on('click', '.ban', function(){
            var ipId = $(this).data('id');

            $('[data-ban-modal-id='+ ipId +']').modal({ backdrop: 'static', keyboard: false })
                    .one('click', '.ban-confirm', function() {
                        $.ajax({
                            url: '/admin/users/banIp/' + ipId,
                            dataType: "text json",
                            type: "POST",
                            beforeSend: function(request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
                            success: function(response) {
                                if(response.success){
                                    $('#message').text(response.message);
                                    var $ipTr = $('[data-ip-id='+ ipId +']');
                                    $ipTr.addClass('danger');
                                    $ipTr.find('.banned-link').removeClass('ban').addClass('unban').html('<i class="fa fa-unlock"></i>');
                                    $form.find('#message').val('');
                                } else {
                                    $('#message').text(response.message);
                                }
                            }
                        });
                    });
        });

        // разбанить
        $('#banned-ips-table').on('click', '.unban', function(){
            var ipId = $(this).data('id');

            $('[data-unban-modal-id='+ ipId +']').modal({ backdrop: 'static', keyboard: false })
                .one('click', '.unban-confirm', function() {
                    $.ajax({
                        url: '/admin/users/unbanIp/' + ipId,
                        dataType: "text json",
                        type: "POST",
                        data: {},
                        beforeSend: function(request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        },
                        success: function(response) {
                            if(response.success){
                                $('#message').text(response.message);
                                var $ipTr = $('[data-ip-id='+ ipId +']');
                                $ipTr.removeClass('danger');
                                $ipTr.find('.banned-link').removeClass('unban').addClass('ban').html('<i class="fa fa-lock"></i>');
                            } else {
                                $('#message').text(response.message);
                            }
                        }
                    });
                });
        });
    </script>
@stop