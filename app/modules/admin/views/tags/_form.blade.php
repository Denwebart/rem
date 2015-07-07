<div class="col-md-12">
    <div class="box">
        <div class="box-title">
            <h3>Редактировать тег</h3>
        </div>
        <div class="box-body row">
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('image', 'Изображение') }}<br/>
                    {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                    {{ $errors->first('image') }}

                    @if($tag->image)
                        {{ $tag->getImage() }}
                    @endif
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    {{ Form::label('title', 'Тег') }}
                    {{ Form::text('title', $tag->title, ['class' => 'form-control', 'placeholder' => 'Новый тег']) }}
                    {{ $errors->first('title') }}
                </div>
            </div>
            <div class="col-md-2">
                {{ Form::submit('Сохранить', ['class' => 'btn btn-success margin-top-25']) }}
                <a href="{{ URL::route('admin.tags.index') }}" class="btn btn-primary">Отмена</a>
            </div>
        </div>
    </div>
</div><!-- ./col -->