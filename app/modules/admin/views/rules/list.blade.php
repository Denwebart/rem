@foreach($rules as $rule)
<tr>
    <td>{{ $rule->position }}</td>
    <td>{{ $rule->title }}</td>
    <td>{{ $rule->description }}</td>
    <td class="status">
        @if($rule->is_published)
            <span class="published" title="Активно" data-toggle="tooltip"></span>
        @else
            <span class="not-published" title="Неактивно" data-toggle="tooltip"></span>
        @endif
    </td>
    <td class="button-column two-buttons">
        <a class="btn btn-info btn-sm margin-right-5" href="{{ URL::route('admin.rules.edit', ['id' => $rule->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]) }}">
            <i class="fa fa-edit "></i>
        </a>

        {{ Form::open(array('method' => 'DELETE', 'url' => URL::route('admin.rules.destroy', ['id' => $rule->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]), 'class' => 'as-button')) }}
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