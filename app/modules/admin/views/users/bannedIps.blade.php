@extends('admin::layouts.admin')

<?php
$title = 'Забаненные IP-адреса';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><i class="fa fa-ban"></i><i class="fa fa-laptop"></i>
            {{ $title }}
            <small>забаненные IP-адреса</small></h1>
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
                <a href="{{ URL::route('admin.users.ips') }}" class="btn btn-primary">
                    Все IP-адреса
                </a>
                <a href="{{ URL::route('admin.users.bannedIps') }}" class="btn btn-primary btn-outline">
                    Забаненные IP-адреса
                </a>
            </div>

            <div id="message"></div>

            <div class="col-xs-6">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped" id="banned-ips-table">
                            <thead>
                            <tr>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.bannedIps', 'IP', 'ip') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.bannedIps', 'Забанен', 'ban_at') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.bannedIps', 'Пользователи', 'users') }}
                                </th>
                                <th class="button-column"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bannedIps as $ip)
                                @include('admin::users.bannedIpRow')
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-left">
                            {{ SortingHelper::paginationLinks($bannedIps) }}
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <div class="col-xs-6">

                <h3>Забанить ip-адрес</h3>

                {{ Form::open([
                    'route' => ['admin.users.banIp', 'ipId' => null],
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
        $("#ban-ip-form").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            var posting = $.post(url, { formData: data });
            posting.done(function(data) {
                if(data.success) {
                    var successContent = '<h3>IP-адрес забанен.</h3>';
                    $form.find('.message').html(successContent);
                    $form.trigger('reset');
                    $form.find('.error').empty();
                    $form.find('.error').parent().removeClass('has-error');
                    // вывод ip-адреса
                    $('#banned-ips-table').find('tbody').prepend(data.ipRowHtml);

                } // success
                else {
                    if(data.fail) {
                        $.each(data.errors, function(index, value) {
                            var errorDiv = '.' + index + '_error';
                            $form.find(errorDiv).parent().addClass('has-error');
                            $form.find(errorDiv).empty().append(value);
                        });
                        $form.find('.message').empty();
                    } else {
                        $form.find('.message').html(data.message);
                    }
                } // user not found
            }); // done
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
    </script>
@stop