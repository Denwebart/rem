@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Редактирование профиля' : 'Редактирование профиля пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    {{ Form::model($user, ['method' => 'POST', 'route' => ['user.update', $user->getLoginForUrl()], 'files' => true], ['id' => 'editProfile']) }}
        <div class="col-lg-3 col-md-3">
            <div class="avatar">

                {{ $user->getAvatar() }}

                @if($user->avatar)
                    <a href="javascript:void(0)" id="delete-avatar">Удалить</a>
                @endif
            </div>
            <div class="form-group">
                {{ Form::file('avatar', ['title' => 'Загрузить аватарку', 'class' => 'btn btn-primary file-inputs']) }}
                {{ $errors->first('avatar') }}
            </div>
        </div>
        <div class="col-lg-7 col-md-7">
            <!-- Breadcrumbs -->
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                    </a>
                </li>
                <li>{{ $title }}</li>
            </ol>

            <div class="row">
                <div class="col-lg-12" id="content">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2>{{{ $user->login }}}</h2>
                            <div class="form-group">
                                {{ Form::label('email', 'Email') }}
                                {{ Form::text('email', $user->email, ['class' => 'form-control']) }}
                                {{ $errors->first('email') }}
                            </div>
                            @if(Auth::user()->isAdmin() && 1 != $user->id)
                                <div class="form-group">
                                    {{ Form::label('role', 'Роль') }}
                                    {{ Form::select('role', User::$roles, $user->role, ['class' => 'form-control']) }}
                                    {{ $errors->first('role') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <div class="button-group">
                                <a href="{{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}}" class="btn btn-primary">
                                    <i class="material-icons">keyboard_arrow_left</i>
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
                        {{ Form::label('car_brand', 'Марка автомобиля / модель') }}
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
                        {{ Form::textarea('description', $user->description, ['class' => 'form-control editor']) }}
                        {{ $errors->first('description') }}
                    </div>
                    {{ Form::hidden('_token', csrf_token()) }}
                </div>
            </div>
        </div>

        <!-- TinyMCE image -->
        {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}

    {{ Form::close() }}
@endsection

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init', ['imagePath' => $user->getImageEditorPath()])
@stop

@section('script')
    @parent

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();

        $(".file-inputs").on("change", function(){
            var file = this.files[0];
            if (file.size > 5242880) {
                $(this).parent().parent().append('Недопустимый размер файла.');
            }
        });
    </script>

    <!-- Delete Avatar -->
    <script type="text/javascript">
        $('#delete-avatar').click(function(){
            if(confirm('Вы уверены, что хотите удалить изображение?')) {
                $.ajax({
                    url: '<?php echo URL::route('user.deleteAvatar', ['login' => $user->getLoginForUrl()]) ?>',
                    dataType: "text json",
                    type: "POST",
                    data: {field: 'avatar'},
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
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