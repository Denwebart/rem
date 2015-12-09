<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Значение</h3>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('value')) has-error @endif">
                @if($setting->key == 'categoriesOnMainPage')
                    {{ Form::select('value[]', Page::getContainer(true, false), explode(',', $setting->value), ['class' => 'form-control', 'multiple' => 'multiple', 'style' => 'height:300px']) }}
                @elseif($setting->key == 'theme')
                    {{ Form::select('value', Setting::$themeValues, $setting->value, ['class' => 'form-control']) }}
                @else
                    @if($setting->type == Setting::TYPE_BOOLEAN)
                        {{ Form::label('value', 'Включена') }}
                        {{ Form::hidden('value', 0, ['id' => 'value_uncheck']) }}
                        {{ Form::checkbox('value', 1, $setting->value, ['class' => 'form-control']) }}
                    @else
                        {{ Form::textarea('value', $setting->value, ['class' => 'form-control']) }}
                    @endif
                @endif
                @if($errors->has('value'))
                    <small class="help-block">
                        {{ $errors->first('value') }}
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
    {{ Form::hidden('backUrl', $backUrl) }}

    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
</div>

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
            $("#settingsForm").submit();
        });
    </script>

@stop