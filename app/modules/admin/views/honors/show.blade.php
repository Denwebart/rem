@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>{{ $honor->title }} <small>просмотр информации о награде</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.honors.index') }}">Награды</a></li>
            <li>{{ $honor->title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-4">
                {{ $honor->getImage() }}
                {{ $honor->description }}
            </div>
            <div class="col-md-8">
                <h3>Наградить</h3>

                {{ Form::open([
                    'action' => ['AdminHonorsController@toReward'],
                    'id' => 'to-reward-form',
                ]) }}

                <div class="message"></div>

                <div class="col-md-10">
                    <div class="form-group">
                        {{ Form::hidden('honor_id', $honor->id, ['id' => 'honor_id']) }}
                        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                        <div class="error"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    {{ Form::submit('Наградить', ['class' => 'btn btn-success']) }}
                </div>

                {{ Form::close() }}

                <hr>
                <h3>Пользователи, у которых есть эта награда</h3>
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        @if(count($honor->users))
                        <table class="table table-hover table-striped" id="users-table">
                            <thead>
                            <tr>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'ID', 'id') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Фото', 'avatar') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Логин', 'login') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Имя', 'fullname') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Дата награждения', '') }}
                                </th>
                                <th>
                                    Все награды
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($honor->users as $user)
                                @include('admin::honors.userRow')
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-left">

                        </div>
                        @else
                            <p>
                                Еще никто не награжден.
                            </p>
                        @endif
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
        $("#name").autocomplete({
            source: "<?php echo URL::route('admin.honors.usersAutocomplete', ['honorId' => $honor->id]) ?>",
            minLength: 1,
            select: function(e, ui) {
                $(this).val(ui.item.value);
                $("#to-reward-form").find('.error').empty();
            }
        });

        $("#to-reward-form").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            var posting = $.post(url, { formData: data });
            posting.done(function(data) {
                if(data.userNotFound) {
                    var errorContent = 'Такого пользователя нет';
                    $form.find('.error').html(errorContent);
                } else {
                    if(data.success) {
                        var successContent = '<h3>Пользователь награжден</h3>';
                        $form.find('.message').html(successContent);
                        $form.trigger('reset');
                        $form.find('.error').empty();

                        // вывод пользователя
                        $('#users-table').find('tbody').prepend(data.userRowHtml);

                    } // success
                    else {
                        var errorContent = '<h3>У пользователя уже есть эта награда</h3>';
                        $form.find('.message').html(errorContent);
                    } // user not found
                }
            }); // done
        });
    </script>

@stop