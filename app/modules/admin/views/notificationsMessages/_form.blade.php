<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Текст уведомления</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::textarea('message', $notificationMessage->message, ['class' => 'form-control']) }}
                {{ $errors->first('message') }}
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
    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ URL::route('admin.rules.index') }}" class="btn btn-primary">Отмена</a>
</div>