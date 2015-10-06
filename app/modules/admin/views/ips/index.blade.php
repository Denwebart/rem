@extends('admin::layouts.admin')

<?php
$title = 'Все IP-адреса';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-laptop"></i>
                    {{ $title }}
                    <small>все IP-адреса</small>
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

            <div class="col-xs-12 margin-bottom-15">
                <a href="{{ URL::route('admin.users.index') }}" class="btn btn-dashed">
                    <span>Все пользователи</span>
                </a>
                <a href="{{ URL::route('admin.users.banned') }}" class="btn btn-dashed">
                    <span>Забаненные пользователи</span>
                </a>
                <a href="{{ URL::route('admin.ips.index') }}" class="btn btn-primary">
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
                            @include('admin::parts.count', ['models' => $ips])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.ips.search'], 'id' => 'search-ips-form', 'class' => 'table-search']) }}
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            {{ Form::text('query', Request::has('query') ? Request::get('query') : null, [
                                'class' => 'form-control',
                                'id' => 'query',
                                'placeholder' => 'Введите ip или логин пользователя'
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
                        <table class="table table-hover table-striped" id="banned-ips-table">
                            <thead>
                            <tr>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.ips.index', 'IP', 'ip') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.ips.index', 'Пользователи', 'users') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.ips.index', 'Комментариев', 'comments') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.ips.index', 'Писем', 'letters') }}
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="ips-list">
                                @include('admin::ips.list', ['ips' => $ips])
                            </tbody>
                        </table>
                        <div id="pagination" class="pull-left">
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
@stop

@section('script')
    @parent

    <script type="text/javascript">

        $("#ip").autocomplete({
            source: "<?php echo URL::route('admin.ips.ipsAutocomplete') ?>",
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
        $('.button-column').on('click', '.ban', function(){
            var ipId = $(this).data('id');

            $('[data-ban-modal-id='+ ipId +']').modal({ backdrop: 'static', keyboard: false })
                    .one('click', '.ban-confirm', function() {
                        $.ajax({
                            url: '/admin/ips/banIp/' + ipId,
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
                        url: '/admin/ips/unbanIp/' + ipId,
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

        $('#query').keyup(function () {
            $("#search-ips-form").submit();
        });
        $("form[id^='search-ips-form']").submit(function(event) {
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
                        $('#ips-list').html(response.ipsListHtmL);
                        $('#pagination').html(response.ipsPaginationHtmL);
                        $('#count').html(response.ipsCountHtmL);
                    }
                },
            });
        });
    </script>
@stop