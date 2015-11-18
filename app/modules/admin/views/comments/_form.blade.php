<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Текст комментария</h3>
            <div class="pull-right author">
                @if($comment->user)
                    {{ $comment->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-right']) }}
                    <span class="pull-right">
                        {{ $comment->user->login }}
                    </span>
                @else
                    {{{ $comment->user_name }}}
                    ({{{ $comment->user_email }}})
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('comment')) has-error @endif">
                {{ Form::textarea('comment', $comment->comment, ['class' => 'form-control editor']) }}
                @if($errors->has('comment'))
                    <small class="help-block">
                        {{ $errors->first('comment') }}
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="col-md-5">

    <div class="box">
        <div class="box-title">

        </div>
        <div class="box-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        Дата создания: {{ DateHelper::dateFormat($comment->created_at) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('is_published', 'Опубликован') }}
                        {{ Form::hidden('is_published', 0, ['id' => 'is_published_uncheck']) }}
                        {{ Form::checkbox('is_published', 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">

    {{ Form::hidden('backUrl', $backUrl) }}

    <!-- TinyMCE image -->
    {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
    {{ Form::hidden('tempPath', $comment->getTempPath(), ['id' => 'tempPath']) }}

    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
</div>

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('admin::tinymce-init')
@stop

@section('script')
    @parent

    <!-- iCheck -->
    <script src="/backend/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type='checkbox'], input[type='radio']").iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });
        });

        // кнопка "Сохранить"
        $(document).on('click', '.save-button', function() {
            $("#commentsForm").submit();
        });
    </script>

@stop