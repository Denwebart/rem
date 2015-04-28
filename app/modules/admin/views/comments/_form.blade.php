<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Текст комментария</h3>
            <div class="pull-right author">
                {{ $comment->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-right']) }}
                <span class="pull-right">
                    {{ $comment->user->login }}
                </span>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::textarea('comment', $comment->comment, ['class' => 'form-control']) }}
                {{ $errors->first('comment') }}
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
    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ URL::route('admin.comments.index') }}" class="btn btn-primary">Отмена</a>
</div>

@section('script')
    @parent

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replace('comment')
    </script>

    <!-- iCheck -->
    <script src="/backend/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type='checkbox'], input[type='radio']").iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });
        });
    </script>

@stop