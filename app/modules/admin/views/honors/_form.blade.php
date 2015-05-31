<div class="col-md-5">
    <div class="box">
        <div class="box-title"></div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('title', 'Название', ['class' => 'control-label']) }}
                {{ Form::text('title', $honor->title, ['class' => 'form-control']) }}
                {{ $errors->first('title') }}
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('image', 'Изображение') }}<br/>
                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                        {{ $errors->first('image') }}
                    </div>
                    <div class="col-sm-6">
                        {{ $honor->getImage() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-7">
    <div class="box">
        <div class="box-title"></div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::textarea('description', $honor->description, ['class' => 'form-control']) }}
                {{ $errors->first('description') }}
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ URL::route('admin.honors.index') }}" class="btn btn-primary">Отмена</a>
</div>

@section('script')
    @parent

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replace('description')
    </script>

@stop