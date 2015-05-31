<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3></h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::textarea('value', $advertising->text, ['class' => 'form-control']) }}
                {{ $errors->first('value') }}
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

                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('is_active', 'Статус') }}
                        {{ Form::hidden('is_active', 0, ['id' => 'is_published_uncheck']) }}
                        {{ Form::checkbox('is_active', 1) }}
                    </div>
                </div>
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