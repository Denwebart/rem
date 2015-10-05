@foreach($tags as $tag)
    <tr>
        <td>{{ $tag->id }}</td>
        <td class="image">{{ $tag->getImage(null, ['width' => '50px']) }}</td>
        <td>
            <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" target="_blank">
                {{ $tag->title }}
            </a>
        </td>
        <td>
            <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" target="_blank">
                {{ count($tag->pages) }}
            </a>
        </td>
        <td class="button-column two-buttons">
            <a class="btn btn-info btn-sm" href="{{ URL::route('admin.tags.edit', $tag->id) }}">
                <i class="fa fa-edit "></i>
            </a>

            {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.tags.destroy', $tag->id), 'class' => 'as-button')) }}
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

        </td>
    </tr>
@endforeach