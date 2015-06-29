<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3></h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('title', 'Заголовок') }}
                {{ Form::text('title', $advertising->title, ['class' => 'form-control']) }}
                {{ $errors->first('title') }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Описание') }}
                {{ Form::textarea('description', $advertising->description, ['class' => 'form-control']) }}
                {{ $errors->first('description') }}
            </div>
            <div class="form-group">
                {{ Form::label('is_active', 'Включен') }}
                {{ Form::hidden('is_active', 0, ['id' => 'is_active_uncheck']) }}
                {{ Form::checkbox('is_active', 1) }}
            </div>
            <div class="form-group">
                {{ Form::label('is_active', 'Отображать заголовок') }}
                {{ Form::hidden('is_show_title', 0, ['id' => 'is_published_uncheck']) }}
                {{ Form::checkbox('is_active', 1) }}
            </div>
            <div class="form-group">
                <h3>Показывать</h3>
                {{ Form::radio('access', Advertising::ACCESS_FOR_ALL, true, ['class'=>'radio']) }}
                {{ Form::label('access', Advertising::$access[Advertising::ACCESS_FOR_ALL]) }}

                {{ Form::radio('access', Advertising::ACCESS_FOR_REGISTERED, false, ['class'=>'radio']) }}
                {{ Form::label('access', Advertising::$access[Advertising::ACCESS_FOR_REGISTERED]) }}

                {{ Form::radio('access', Advertising::ACCESS_FOR_GUEST, false, ['class'=>'radio']) }}
                {{ Form::label('access', Advertising::$access[Advertising::ACCESS_FOR_GUEST]) }}
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
                {{ Form::label('area', 'Область') }}
                {{ Form::select('area', ['' => '-'] + Advertising::$areas, $advertising->area, ['class' => 'form-control']) }}
                {{ $errors->first('area') }}
            </div>

            <div class="form-group">
                {{ Form::label('code', 'HTML/JavaScript') }}
                {{ Form::textarea('code', $advertising->code, ['class' => 'form-control']) }}
                {{ $errors->first('code') }}
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ URL::route('admin.advertising.index') }}" class="btn btn-primary">Отмена</a>
</div>

@section('script')
    @parent

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replace('text')
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