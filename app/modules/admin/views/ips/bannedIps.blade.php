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

        {{--<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">--}}
            {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
            {{--<li class="active"><a href="{{ URL::route('admin.users.index') }}">Пользователи</a></li>--}}
            {{--<li class="active">{{ $title }}</li>--}}
        {{--</ol>--}}
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-md-8 col-sm-8 col-xs-12 margin-bottom-15">
                <a href="{{ URL::route('admin.users.index') }}" class="btn btn-dashed">
                    <span>Все пользователи</span>
                </a>
                <a href="{{ URL::route('admin.users.banned') }}" class="btn btn-dashed">
                    <span>Забаненные пользователи</span>
                </a>
                <a href="{{ URL::route('admin.ips.index') }}" class="btn btn-dashed">
                    <span>Все IP-адреса</span>
                </a>
                <a href="{{ URL::route('admin.ips.bannedIps') }}" class="btn btn-primary">
                    <span>Забаненные IP-адреса</span>
                </a>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{ Form::open([
                    'route' => ['admin.ips.banIp', 'ipId' => null],
                    'id' => 'ban-ip-form',
                ]) }}

                <div class="form-group @if($errors->has('ip')) has-error @endif">
                    <div class="input-group">
                        {{ Form::text('ip', null, ['class' => 'form-control', 'placeholder' => 'Забанить ip-адрес', 'id' => 'ip']) }}
                        <span class="input-group-btn">
                            {{ Form::submit('Забанить', ['class' => 'btn btn-success']) }}
                        </span>
                    </div>
                    <small class="ip_error error help-block">
                        {{ $errors->first('ip') }}
                    </small>
                </div>

                {{--<div class="col-md-10">--}}
                    {{--<div class="form-group">--}}
                        {{--{{ Form::text('ip', null, ['class' => 'form-control', 'placeholder' => 'Забанить ip-адрес', 'id' => 'ip']) }}--}}
                        {{--<div class="ip_error error"></div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-2">--}}
                    {{--{{ Form::submit('Забанить', ['class' => 'btn btn-success']) }}--}}
                {{--</div>--}}

                {{ Form::hidden('_token', csrf_token()) }}
                {{ Form::close() }}
            </div>

            <div id="message"></div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div id="count" class="count">
                            @include('admin::parts.count', ['models' => $ips])
                        </div>
                    </div>
                    {{ Form::open(['method' => 'GET', 'route' => ['admin.ips.search'], 'id' => 'search-ips-form', 'class' => 'table-search']) }}
                    <div class="col-lg-3 col-lg-offset-6 col-md-3 col-md-offset-6 col-sm-3 col-sm-offset-6 col-xs-12">
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
                                    Пользователи
{{--                                    {{ SortingHelper::sortingLink('admin.ips.index', 'Пользователи', 'users') }}--}}
                                </th>
                                <th>
                                    Комментариев
{{--                                    {{ SortingHelper::sortingLink('admin.ips.index', 'Комментариев', 'comments') }}--}}
                                </th>
                                <th>
                                    Писем
{{--                                    {{ SortingHelper::sortingLink('admin.ips.index', 'Писем', 'letters') }}--}}
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
                    if(response.fail) {
                        $.each(response.errors, function(index, value) {
                            var errorDiv = '.' + index + '_error';
                            $form.find(errorDiv).parent().addClass('has-error');
                            $form.find(errorDiv).empty().append(value);
                        });
                        $form.find('.message').empty();
                    }
                    if(response.success){
                        var successContent = '<h3>IP-адрес забанен.</h3>';
                        $form.find('.message').html(successContent);
                        $form.find('.error').empty();
                        $form.find('.error').parent().removeClass('has-error');
                        // вывод ip-адреса
                        $('#banned-ips-table').find('tbody').prepend(response.ipRowHtml);
                    } else {
                        $('#site-messages').prepend(response.message);
                    }
                    $form.trigger('reset');
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
                                $('#site-messages').prepend(response.message);
                            } else {
                                $('#site-messages').prepend(response.message);
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