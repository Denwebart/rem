@foreach($comments as $comment)
    <tr @if($comment->is_deleted) class="danger" @elseif($comment->created_at > $headerWidget->getLastActivity()) class="info" @endif>
        <td>{{ $comment->id }}</td>
        <td>
            @if($comment->user)
                <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
                    {{ $comment->user->getAvatar('mini', ['width' => '25px']) }}
                </a>
            @else
                {{{ $comment->user_name }}}
                <br/>
                ({{{ $comment->user_email }}})
            @endif
        </td>
        <td>
            @if($comment->ip)
                {{ $comment->ip->ip }}
            @endif
        </td>
        <td>
            @if($comment->page)
                <a href="{{ URL::to($comment->getUrl()) }}">
                    {{ $comment->page->getTitle() }}
                </a>
            @else
                <i>страница удалена</i>
            @endif
        </td>
        <td>{{ $comment->comment }}</td>
        <td class="status">
            @if(!$comment->is_deleted)
                @if($comment->is_published)
                    <span class="published" title="Опубликован" data-toggle="tooltip"></span>
                @else
                    <span class="not-published" title="Не опубликован" data-toggle="tooltip"></span>
                @endif
            @else
                <span class="deleted" title="Удалён" data-toggle="tooltip"></span>
            @endif
        </td>
        <td class="date">{{ DateHelper::dateFormat($comment->created_at) }}</td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ URL::route('admin.comments.edit', $comment->id) }}">
                <i class="fa fa-edit "></i>
            </a>

            @if(Auth::user()->isAdmin())
                {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.comments.destroy', $comment->id), 'class' => 'as-button')) }}
                <button type="submit" class="btn btn-danger btn-sm" name="destroy">
                    <i class='fa fa-trash-o'></i>
                </button>
                {{ Form::hidden('_token', csrf_token()) }}
                {{ Form::close() }}
            @endif

            <div id="confirm" class="modal fade">
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
                            <button type="button" class="btn btn-success" data-dismiss="modal" id="delete">Да</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Нет</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </td>
    </tr>
@endforeach