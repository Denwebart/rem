@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Редактирование профиля' : 'Редактирование профиля пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    {{ Form::model($user, ['method' => 'POST', 'route' => ['user.update', $user->getLoginForUrl()], 'files' => true], ['id' => 'editProfile']) }}
        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
            <div class="row">
                <div class="col-md-10 col-xs-10" style="padding-right: 0">
                    <div class="profile-user-avatar">
                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="">
                            {{ $user->getAvatar(null, ['class' => 'avatar']) }}
                        </a>
                    </div>
                    <div class="form-group">
                        {{ Form::file('avatar', ['title' => 'Загрузить аватарку', 'class' => 'btn btn-primary btn-sm btn-full file-inputs']) }}
                        {{ $errors->first('avatar') }}
                    </div>
                </div>
                <div class="col-md-2 col-xs-2" style="padding: 0">
                    @if($user->avatar)
                        <a href="javascript:void(0)" class="delete-avatar pull-right" title="Удалить аватарку" data-toggle="tooltip">
                            <i class="material-icons">delete</i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-7">
            <!-- Breadcrumbs -->
            <ol class="breadcrumb">
                <li class="home-page">
                    <a href="{{ URL::to('/') }}">
                        <i class="material-icons">home</i>
                    </a>
                </li>
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                    </a>
                </li>
                <li class="hidden-md hidden-xs">{{ $title }}</li>
            </ol>

            <div class="row">
                <div class="col-lg-12" id="content">

                    <div class="row hidden-lg hidden-md">
                        <div class="col-sm-12 col-xs-12">
                            <div id="user-info-mobile">
                                <a class="pull-left avatar-link gray-background @if($user->is_banned) banned @endif" href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                    {{ $user->getAvatar('mini', ['class' => 'media-object avatar circle']) }}
                                    @if($user->isOnline())
                                        <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                                    @else
                                        <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                                    @endif
                                </a>
                                <div class="pull-left">
                                    <div class="form-group">
                                        {{ Form::file('avatar_mobile', ['title' => 'Загрузить аватарку', 'class' => 'btn btn-primary btn-sm btn-full file-inputs']) }}
                                        {{ $errors->first('avatar') }}
                                    </div>
                                </div>
                                <div class="pull-left">
                                    @if($user->avatar)
                                        <a href="javascript:void(0)" class="delete-avatar pull-right" title="Удалить аватарку" data-toggle="tooltip">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-sm-12 col-xs-12" id="users-menu-mobile">
                            @include('cabinet::user.menuMobile')
                        </div>
                    </div>

                    <div class="well">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 pull-right">
                                <div class="button-group">
                                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="btn btn-primary btn-sm">
                                        <i class="material-icons">keyboard_arrow_left</i>
                                        <span class="hidden-xxs">Отмена</span>
                                    </a>

                                    {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 pull-left">
                                <h2>{{ $user->login }}</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('email', 'Email') }}
                                    {{ Form::text('email', $user->email, ['class' => 'form-control']) }}
                                    {{ $errors->first('email') }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                @if(Auth::user()->isAdmin() && 1 != $user->id)
                                    <div class="form-group">
                                        {{ Form::label('role', 'Роль') }}
                                        {{ Form::select('role', User::$roles, $user->role, ['class' => 'form-control']) }}
                                        {{ $errors->first('role') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('firstname', 'Имя') }}
                                    {{ Form::text('firstname', $user->firstname, ['class' => 'form-control']) }}
                                    {{ $errors->first('firstname') }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('lastname', 'Фамилия') }}
                                    {{ Form::text('lastname', $user->lastname, ['class' => 'form-control']) }}
                                    {{ $errors->first('lastname') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('country', 'Страна') }}
                                    {{ Form::text('country', $user->country, ['class' => 'form-control', 'id' => 'country']) }}
                                    {{ $errors->first('country') }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('city', 'Город') }}
                                    {{ Form::text('city', $user->city, ['class' => 'form-control', 'id' => 'city']) }}
                                    {{ $errors->first('city') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('car_brand', 'Марка автомобиля / модель') }}
                                    {{ Form::text('car_brand', $user->car_brand, ['class' => 'form-control']) }}
                                    {{ $errors->first('car_brand') }}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('profession', 'Профессия') }}
                                    {{ Form::text('profession', $user->profession, ['class' => 'form-control']) }}
                                    {{ $errors->first('profession') }}
                                </div>
                            </div>
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
        </div>

        <!-- TinyMCE image -->
        {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
        {{ Form::hidden('tempPath', $user->getTempPath(), ['id' => 'tempPath']) }}

    {{ Form::close() }}
@stop

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init')
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
        $('.delete-avatar').click(function(){
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
                            $('#site-messages').prepend(response.message);
                            $('.delete-avatar').css('display', 'none');
                            $('.profile-user-avatar img').attr('src', response.imageUrl).addClass('avatar-default');
                            $('.avatar-link img').attr('src', response.imageUrlMini).addClass('avatar-default');
                            $('.widget-user img').attr('src', response.imageUrlMini).addClass('avatar-default');
                        }
                    }
                });
            }
        });
    </script>

@stop