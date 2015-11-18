<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3></h3>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('title')) has-error @endif">
                {{ Form::label('title', 'Заголовок') }}
                {{ Form::text('title', $rule->title, ['class' => 'form-control']) }}
                @if($errors->has('title'))
                    <small class="help-block">
                        {{ $errors->first('title') }}
                    </small>
                @endif
            </div>

            <div class="form-group @if($errors->has('description')) has-error @endif">
                {{ Form::label('description', 'Текст правила') }}
                {{ Form::textarea('description', $rule->description, ['class' => 'form-control editor']) }}
                @if($errors->has('description'))
                    <small class="help-block">
                        {{ $errors->first('description') }}
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
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group @if($errors->has('position')) has-error @endif">
                        {{ Form::label('position', 'Номер правила') }}
                        {{ Form::text('position', $rule->position, ['class' => 'form-control']) }}
                        @if($errors->has('position'))
                            <small class="help-block">
                                {{ $errors->first('position') }}
                            </small>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if($errors->has('is_published')) has-error @endif">
                        {{ Form::label('is_published', 'Статус') }}
                        {{ Form::hidden('is_published', 0, ['id' => 'is_published_uncheck']) }}
                        {{ Form::checkbox('is_published', 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <!-- TinyMCE image -->
    {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
    {{ Form::hidden('tempPath', $rule->getTempPath(), ['id' => 'tempPath']) }}

    {{ Form::hidden('backUrl', $backUrl) }}
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
            $("#ruleForm").submit();
        });
    </script>
@stop