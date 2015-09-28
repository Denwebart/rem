@extends('admin::layouts.admin')

<?php
$title = 'Забаненные IP-адреса';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-ban"></i>
                    <i class="fa fa-laptop"></i>
                    {{ $title }}
                    <small>забаненные IP-адреса</small>
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
                <a href="{{ URL::route('admin.users.banned') }}" class="btn btn-primary">
                    Забаненные пользователи
                </a>
                <a href="{{ URL::route('admin.ips.index') }}" class="btn btn-primary">
                    Все IP-адреса
                </a>
                <a href="{{ URL::route('admin.ips.bannedIps') }}" class="btn btn-primary btn-outline">
                    Забаненные IP-адреса
                </a>
            </div>

            <div id="message"></div>

            <div class="col-xs-6">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div id="count" class="count">
                            @include('admin::parts.count', ['models' => $ips])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.ips.search'], 'id' => 'search-ips-form', 'class' => 'table-search']) }}
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                                    {{ SortingHelper::sortingLink('admin.ips.bannedIps', 'IP', 'ip') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.ips.bannedIps', 'Забанен', 'ban_at') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.ips.bannedIps', 'Пользователи', 'users') }}
                                </th>
                                <th class="button-column"></th>
                            </tr>
                            </thead>
                            <tbody id="ips-list">
                                @include('admin::ips.bannedList', ['ips' => $ips])
                            </tbody>
                        </table>
                        <div id="pagination" class="pull-left">
                            {{ SortingHelper::paginationLinks($ips) }}
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <div class="col-xs-6">

                <h3>Забанить ip-адрес</h3>

                {{ Form::open([
                    'route' => ['admin.ips.banIp', 'ipId' => null],
                    'id' => 'ban-ip-form',
                ]) }}

                    <div class="message"></div>

                    <div class="col-md-10">
                        <div class="form-group">
                            {{ Form::text('ip', null, ['class' => 'form-control', 'id' => 'ip']) }}
                            <div class="ip_error error"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        {{ Form::submit('Забанить', ['class' => 'btn btn-success']) }}
                    </div>
                    {{ Form::hidden('_token', csrf_token()) }}
                {{ Form::close() }}
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
        $("#ban-ip-form").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            $.ajax({
                url: url,
                dataType: "text json",
                type: "POST",
                data: {formData: data},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        var successContent = '<h3>IP-адрес забанен.</h3>';
                        $form.find('.message').html(successContent);
                        $form.trigger('reset');
                        $form.find('.error').empty();
                        $form.find('.error').parent().removeClass('has-error');
                        // вывод ip-адреса
                        $('#banned-ips-table').find('tbody').prepend(response.ipRowHtml);
                    } else {
                        if(response.fail) {
                            $.each(response.errors, function(index, value) {
                                var errorDiv = '.' + index + '_error';
                                $form.find(errorDiv).parent().addClass('has-error');
                                $form.find(errorDiv).empty().append(value);
                            });
                            $form.find('.message').empty();
                        } else {
                            $form.find('.message').html(data.message);
                        }
                    }
                }
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
                                $('[data-ip-id='+ ipId +']').remove();
                                $('#message').text(response.message);
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
                data: {searchData: data, view: 'bannedList', route: 'bannedIps'},
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