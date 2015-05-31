@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Комментарии  <small>комментарии к статьям</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active">Комментарии</li>
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Изображение', 'image') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Название', 'title') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Алиас', 'alias') }}</th>
                                <th>Описание</th>
                                <th>Пользователи</th>
                                <th class="button-column">
                                    <a class="btn btn-success btn-sm" href="{{ URL::route('admin.honors.create') }}">
                                        <i class="fa fa-plus "></i> Создать
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($honors as $honor)
                                <tr>
                                    <td>{{ $honor->id }}</td>
                                    <td>
                                        <a href="{{ URL::route('admin.honors.show', ['id' => $honor->id]) }}">
                                            {{ $honor->getImage(null, ['width' => '50px']) }}
                                        </a>
                                    </td>
                                    <td>{{ $honor->title }}</td>
                                    <td>{{ $honor->alias }}</td>
                                    <td>{{ $honor->description }}</td>
                                    <td>
                                        @foreach($honor->users as $key => $user)
                                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                                {{ $user->getAvatar('mini', ['width' => '25px']) }}
                                                <span>{{ $user->login }}</span>
                                            </a>
                                            {{ (count($honor->users) - 1 > $key) ? "," : "" }}
                                        @endforeach
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ URL::route('admin.honors.edit', $honor->id) }}">
                                            <i class="fa fa-edit "></i>
                                        </a>

                                        @if(Auth::user()->isAdmin())
                                        {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.honors.destroy', $honor->id), 'class' => 'as-button')) }}
                                        <button type="submit" class="btn btn-danger btn-sm" name="destroy">
                                            <i class='fa fa-trash-o'></i>
                                        </button>
                                        {{ Form::close() }}
                                        @endif

                                        <div class="modal fade confirm">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Удаление</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Вы уверены, что хотите удалить?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-success delete" data-dismiss="modal">Да</button>
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Нет</button>
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
                            {{ $honors->links() }}
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
        $('button[name="destroy"]').on('click', function(e){
            var $form=$(this).closest('form');
            e.preventDefault();
            $('.confirm').modal({ backdrop: 'static', keyboard: false })
                .one('click', '.delete', function() {
                    $form.trigger('submit'); // submit the form
                });
        });
    </script>
@stop