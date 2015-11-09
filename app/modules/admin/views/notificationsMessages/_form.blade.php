<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Текст уведомления</h3>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('message')) has-error @endif">
                {{ Form::textarea('message', $notificationMessage->message, ['class' => 'form-control']) }}
                @if($errors->has('message'))
                    <small class="help-block">
                        {{ $errors->first('message') }}
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
            {{ $notificationMessage->variables }}
        </div>
    </div>
</div>

<div class="col-md-12">
    {{ Form::hidden('backUrl', $backUrl) }}

    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
</div>