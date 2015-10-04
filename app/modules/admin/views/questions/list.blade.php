@foreach($pages as $page)
    <tr @if($page->created_at > $headerWidget->getLastActivity()) class="info" @endif>
        <td>{{ $page->id }}</td>
        <td class="author">
            <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                {{ $page->user->getAvatar('mini', ['width' => '25px']) }}
            </a>
        </td>
        <td>
            {{ $page->getImage('mini', ['width' => '50px']) }}
        </td>
        <td>
            <a href="{{ URL::to($page->getUrl()) }}" target="_blank">
                {{ $page->getTitle() }}
            </a>
        </td>
        <td>
            @if(count($page->bestComments))
                <i class="glyphicon glyphicon-ok" title="Есть решение"></i>
            @endif
            <a href="{{ URL::to($page->getUrl()) }}#answers" target="_blank">
                {{ count($page->publishedComments) }}
            </a>
        </td>
        <td>
            @if($page->parent)
                <a href="{{ URL::to($page->parent->getUrl()) }}" target="_blank">
                    {{ $page->parent->getTitle() }}
                </a>
            @else
                Нет
            @endif
        </td>
        <td class="status">
            @if($page->is_published)
                <span class="published" title="Опубликована" data-toggle="tooltip"></span>
            @else
                <span class="not-published" title="Не опубликована" data-toggle="tooltip"></span>
            @endif
        </td>
        <td class="date">
            {{ DateHelper::dateFormat($page->created_at, false) }}
            <br>
            {{ date('H:i', strtotime($page->created_at)) }}
        </td>
        <td class="date">
            @if(!is_null($page->published_at))
                {{ DateHelper::dateFormat($page->published_at, false) }}
                <br>
                {{ date('H:i', strtotime($page->published_at)) }}
            @else
                -
            @endif
        </td>
        <td class="button-column two-buttons">
            <a class="btn btn-info btn-sm" href="{{ URL::route('admin.questions.edit', $page->id) }}">
                <i class="fa fa-edit "></i>
            </a>

            @if(Auth::user()->isAdmin())
                {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.questions.destroy', $page->id), 'class' => 'as-button')) }}
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