@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li>Редактирование профиля</li>
            </ol>
        </div>

        {{ Form::model($user, ['method' => 'POST', 'route' => ['user.update', $user->id], 'files' => true], ['id' => 'editProfile']) }}
        <div class="col-lg-3">
            <div class="avatar">
                @if($user->avatar)
                    {{ HTML::image('/uploads/' . $user->login . '/' . $user->avatar, $user->login, ['class' => 'img-responsive']) }}
                    <a href="javascript:void(0)" id="delete-avatar">Удалить</a>
                    @section('script')
                        @parent

                        <script type="text/javascript">
                            $('#delete-avatar').click(function(){
                                if(confirm('Вы уверены, что хотите удалить изображение?')) {
                                    $.ajax({
                                        url: '<?php echo URL::route('user.deleteAvatar', ['id' => $user->id]) ?>',
                                        dataType: "text json",
                                        type: "POST",
                                        data: {field: 'avatar'},
                                        success: function(response) {
                                            if(response.success){
                                                $('#delete-avatar').css('display', 'none');
                                                $('.avatar img').attr('src', response.imageUrl).addClass('avatar-default');
                                            }
                                        }
                                    });
                                }
                            });
                        </script>
                    @stop
                @else
                    {{ HTML::image(Config::get('settings.defaultAvatar'), $user->login, ['class' => 'img-responsive avatar-default']) }}
                @endif
            </div>
            <div class="form-group">
                {{ Form::file('avatar', ['title' => 'Загрузить аватарку', 'class' => 'btn btn-primary file-inputs']) }}
                {{ $errors->first('avatar') }}
            </div>
        </div>
        <div class="col-lg-9">

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {{ Form::label('login', 'Логин') }}
                        {{ Form::text('login', $user->login, ['class' => 'form-control']) }}
                        {{ $errors->first('login') }}
                    </div>
                    {{--<h2>{{{ $user->login }}}</h2>--}}
                </div>
                <div class="col-lg-6">
                    <div class="button-group">
                        <a href="{{{ URL::route('user.profile', ['login' => $user->login]) }}}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-arrow-left"></span>
                            Назад
                        </a>

                        {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
                    </div>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-lg-6">
                    {{ Form::label('firstname', 'Имя') }}
                    {{ Form::text('firstname', $user->firstname, ['class' => 'form-control']) }}
                    {{ $errors->first('firstname') }}
                </div>
                <div class="col-lg-6">
                    {{ Form::label('lastname', 'Фамилия') }}
                    {{ Form::text('lastname', $user->lastname, ['class' => 'form-control']) }}
                    {{ $errors->first('lastname') }}
                </div>
            </div>

            <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::text('email', $user->email, ['class' => 'form-control']) }}
                {{ $errors->first('email') }}
            </div>

            <div class="form-group">
                {{ Form::label('country', 'Страна') }}
                {{ Form::text('country', $user->country, ['class' => 'form-control']) }}
                {{ $errors->first('country') }}
            </div>

            <div class="form-group">
                {{ Form::label('city', 'Город') }}
                {{ Form::text('city', $user->city, ['class' => 'form-control']) }}
                {{ $errors->first('city') }}
            </div>

            <div class="form-group">
                {{ Form::label('car_brand', 'Марка автомобиля') }}
                {{ Form::text('car_brand', $user->car_brand, ['class' => 'form-control']) }}
                {{ $errors->first('car_brand') }}
            </div>

            <div class="form-group">
                {{ Form::label('profession', 'Профессия') }}
                {{ Form::text('profession', $user->profession, ['class' => 'form-control']) }}
                {{ $errors->first('profession') }}
            </div>

            <div class="form-group">
                {{ Form::label('description', 'О себе') }}
                {{ Form::textarea('description', $user->description, ['class' => 'form-control']) }}
                {{ $errors->first('description') }}
            </div>

        </div>
        {{ Form::close() }}
    </div>
@stop

@section('script')
    @parent

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replace('description', {
            toolbar: [
                [ 'Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo' ],
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
                { name: 'links', items: [ 'Link', 'Unlink'] },
            ]
        })
    </script>

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();
    </script>
@stop