@foreach($pages as $page)
    <tr>
        <td>{{ $page->id }}</td>
        <td class="author">
            <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                {{ $page->user->getAvatar('mini', ['width' => '25px']) }}
            </a>
        </td>
        @if($page->is_container)
            <td class="category">
                <i class="fa fa-folder"></i>
            </td>
        @else
            <td class="page">
                <i class="fa fa-file-text-o"></i>
            </td>
        @endif
        <td>
            <a href="{{ URL::to($page->getUrl()) }}" target="_blank">
                {{ $page->getTitle() }}
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
        <td>{{ $page->meta_title }}</td>
        <td>{{ $page->meta_desc }}</td>
        <td>{{ $page->meta_key }}</td>
        <td class="button-column two-buttons">
            <a class="btn btn-info btn-sm margin-right-5" href="{{ URL::route('admin.pages.edit', ['id' => $page->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]) }}">
                <i class="fa fa-edit"></i>
            </a>

            @if(Auth::user()->isAdmin() && $page->type != Page::TYPE_SYSTEM_PAGE)
                {{ Form::open(array('method' => 'DELETE', 'url' => URL::route('admin.pages.destroy', ['id' => $page->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]), 'class' => 'as-button')) }}
                <button type="submit" class="btn btn-danger btn-sm" name="destroy">
                    <i class='fa fa-trash-o'></i>
                </button>
                {{ Form::hidden('_token', csrf_token()) }}
                {{ Form::close() }}

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
            @endif

        </td>
    </tr>
@endforeach