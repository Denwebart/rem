<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Шаблон письма</h3>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('subject')) has-error @endif">
                {{ Form::label('subject', 'Тема') }}
                {{ Form::text('subject', $emailTemplate->subject, ['class' => 'form-control']) }}
                @if($errors->has('subject'))
                    <small class="help-block">
                        {{ $errors->first('subject') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('html')) has-error @endif">
                {{ Form::label('html', 'Текст письма') }}
                {{ Form::textarea('html', $emailTemplate->html, ['class' => 'form-control editor']) }}
                @if($errors->has('html'))
                    <small class="help-block">
                        {{ $errors->first('html') }}
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="col-md-5">
    <div class="box">
        <div class="box-title">
            <h3>Доступные переменные</h3>
        </div>
        <div class="box-body">
            {{ $emailTemplate->variables }}
        </div>
    </div>
</div>

<div class="col-md-12">
    {{ Form::hidden('backUrl', $backUrl) }}

    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
</div>

@section('style')
    @parent
    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('admin::tinymce-init', ['imagePath' => $emailTemplate->getImageEditorPath()])
@stop