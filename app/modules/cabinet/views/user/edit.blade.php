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
                        {{ $user->getAvatar(null, ['class' => 'avatar']) }}
                        @if($user->avatar)
                            <a href="javascript:void(0)" class="delete-avatar" title="Удалить аватарку" data-toggle="tooltip">
                                <i class="material-icons">delete</i>
                            </a>
                        @endif
                    </div>
                    <div class="form-group">
                        {{ Form::file('avatar', ['title' => 'Загрузить аватарку', 'class' => 'btn btn-primary btn-sm btn-full file-inputs ajax-upload']) }}
                        <small class="info">
                            {{ Config::get('settings.maxImageSizeInfo') }}
                        </small>
                        <small class="image_error error text-danger">
                            {{ $errors->first('avatar') }}
                        </small>
                    </div>
                </div>
                <div class="col-md-2 col-xs-2" style="padding: 0">
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-7">
            <!-- Breadcrumbs -->
            @include('widgets.breadcrumbs', ['items' => [
                [
                    'title' => Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login,
                    'url' => URL::route('user.profile', ['login' => $user->getLoginForUrl()])
                ],
                [
                    'title' => $title
                ]
            ]])

            <div class="row">
                <div class="col-lg-12" id="content">

                    <div class="row hidden-lg hidden-md">
                        <div class="col-sm-12 col-xs-12">
                            <div id="user-info-mobile" class="pull-left">
                                <a class="pull-left avatar-link gray-background @if($user->is_banned) banned @endif" href="javascript:void(0)">
                                    {{ $user->getAvatar('mini', ['class' => 'media-object avatar circle']) }}
                                    @if($user->avatar)
                                        <a href="javascript:void(0)" class="delete-avatar pull-right" title="Удалить аватарку" data-toggle="tooltip">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    @endif
                                    <a href="javascript:void(0)" id="delete-temp-image" class="delete-temp-image pull-right" style="display: none">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </a>
                            </div>
                            <div class="pull-left margin-left-20">
                                <div class="form-group">
                                    {{ Form::file('avatar_mobile', ['title' => 'Загрузить аватарку', 'class' => 'btn btn-primary btn-sm btn-full file-inputs ajax-upload']) }}
                                    <small class="info">
                                        {{ Config::get('settings.maxImageSizeInfo') }}
                                    </small>
                                    <small class="image_error error text-danger">
                                        {{ $errors->first('avatar') }}
                                    </small>
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
                                    <a href="{{ $backUrl }}" class="btn btn-primary btn-sm">
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
                                <div class="form-group @if($errors->has('email')) has-error @endif">
                                    {{ Form::label('email', 'Email', ['class' => 'control-label']) }}
                                    {{ Form::text('email', $user->email, ['class' => 'form-control']) }}
                                    <small class="email_error error text-danger">
                                        {{ $errors->first('email') }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                @if(Auth::user()->isAdmin() && (1 != $user->id && Auth::user()->id != $user->id))
                                    <div class="form-group @if($errors->has('role')) has-error @endif">
                                        {{ Form::label('role', 'Роль', ['class' => 'control-label']) }}
                                        {{ Form::select('role', User::$roles, $user->role, ['class' => 'form-control']) }}
                                        <small class="role_error error text-danger">
                                            {{ $errors->first('role') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group @if($errors->has('firstname')) has-error @endif">
                                    {{ Form::label('firstname', 'Имя', ['class' => 'control-label']) }}
                                    {{ Form::text('firstname', $user->firstname, ['class' => 'form-control']) }}
                                    <small class="firstname_error error text-danger">
                                        {{ $errors->first('firstname') }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group @if($errors->has('lastname')) has-error @endif">
                                    {{ Form::label('lastname', 'Фамилия', ['class' => 'control-label']) }}
                                    {{ Form::text('lastname', $user->lastname, ['class' => 'form-control']) }}
                                    <small class="lastname_error error text-danger">
                                        {{ $errors->first('lastname') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group @if($errors->has('country')) has-error @endif">
                                    {{ Form::label('country', 'Страна', ['class' => 'control-label']) }}
                                    {{ Form::text('country', $user->country, ['class' => 'form-control', 'id' => 'country']) }}
                                    <small class="country_error error text-danger">
                                        {{ $errors->first('country') }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group @if($errors->has('city')) has-error @endif">
                                    {{ Form::label('city', 'Город', ['class' => 'control-label']) }}
                                    {{ Form::text('city', $user->city, ['class' => 'form-control', 'id' => 'city']) }}
                                    <small class="city_error error text-danger">
                                        {{ $errors->first('city') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group @if($errors->has('car_brand')) has-error @endif">
                                    {{ Form::label('car_brand', 'Марка автомобиля / модель', ['class' => 'control-label']) }}
                                    {{ Form::text('car_brand', $user->car_brand, ['class' => 'form-control']) }}
                                    <small class="car_brand_error error text-danger">
                                        {{ $errors->first('car_brand') }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group @if($errors->has('profession')) has-error @endif">
                                    {{ Form::label('profession', 'Профессия', ['class' => 'control-label']) }}
                                    {{ Form::text('profession', $user->profession, ['class' => 'form-control']) }}
                                    <small class="profession_error error text-danger">
                                        {{ $errors->first('profession') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('description')) has-error @endif">
                            {{ Form::label('description', 'О себе', ['class' => 'control-label']) }}
                            {{ Form::textarea('description', $user->description, ['class' => 'form-control editor']) }}
                            <small class="description_error error text-danger">
                                {{ $errors->first('description') }}
                            </small>
                        </div>
                        {{ Form::hidden('_token', csrf_token()) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- TinyMCE image -->
        {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
        {{ Form::hidden('tempPath', $user->getTempPath(), ['id' => 'tempPath']) }}

        {{ Form::hidden('backUrl', $backUrl) }}
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
                            setTimeout(function() {
                                hideSiteMessage($('.site-message'));
                            }, 2000);

                            $('.delete-avatar').css('display', 'none');
                            $('.delete-temp-image').hide();
                            $('.profile-user-avatar img').attr('src', response.imageUrl).addClass('avatar-default');
                            $('.avatar-link img').attr('src', response.imageUrlMini).addClass('avatar-default');
                            $('.widget-user img').attr('src', response.imageUrlMini).addClass('avatar-default');
                        }
                    }
                });
            }
        });
    </script>

    <!-- Загрузка изображения ajax -->
    <script type="text/javascript">

        var isValidFileSize = true;

        $('.ajax-upload').on('change', function () {
            if (this.files[0].size > 5242880) {
                $('form').find('.image_error').parent().addClass('has-error');
                $('form').find('.image_error').empty().append('Недопустимый размер файла.').show();
                isValidFileSize = false;
            } else {
                isValidFileSize = true;
                var fileData = new FormData();
                fileData.append('image', $(this)[0].files[0]);
                fileData.append('tempPath', $('#tempPath').val());
                fileData.append('class', ' avatar');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo URL::route('uploadIntoTemp', ['field' => 'avatar', 'watermark' => 0]) ?>',
                    data: fileData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.fail) {
                            $.each(response.errors, function(index, value) {
                                var errorDiv = '.' + index + '_error';
                                $('form').find(errorDiv).parent().addClass('has-error');
                                $('form').find(errorDiv).empty().append(value).show();
                            });
                        }
                        if(response.success) {
                            $('.profile-user-avatar').html(response.imageHtml);

                            $('.delete-temp-image').show();
                            $('.delete-avatar').hide();
                            $('.avatar-link img').attr('src', response.imagePath + 'mini_' + response.imageName).removeClass('avatar-default');
                            $('.widget-user img').attr('src', response.imagePath + 'mini_' + response.imageName).removeClass('avatar-default');

                            $('form').find('.image_error').parent().removeClass('has-error');
                            $('form').find('.image_error').empty().hide();
                        }
                    }
                });
            }
        });

        <!-- Удаление временного изображения ajax -->
        $('.profile-user-avatar, #user-info-mobile').on('click', '#delete-temp-image', function(){
            var $button = $(this);
            if(confirm('Вы уверены, что хотите удалить изображение?')) {
                var imageName = $(this).parent().parent().find('.file-input-name');
                $.ajax({
                    url: '<?php echo URL::route('deleteFromTemp') ?>',
                    dataType: "text json",
                    type: "POST",
                    data: {'imageName': imageName.text(), 'tempPath': $('#tempPath').val()},
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $('#site-messages').prepend(response.message);
                            setTimeout(function() {
                                hideSiteMessage($('.site-message'));
                            }, 2000);

                            $button.css('display', 'none');
                            $('.delete-temp-image, #delete-temp-image').hide();
                            imageName.text('');
                            $('.ajax-upload').val('');
                            $('.profile-user-avatar img').attr('src', '<?php echo Config::get('settings.defaultAvatar') ?>').addClass('avatar-default');
                            $('.avatar-link img').attr('src', '<?php echo Config::get('settings.mini_defaultAvatar') ?>').addClass('avatar-default');
                            $('.widget-user img').attr('src', '<?php echo Config::get('settings.mini_defaultAvatar') ?>').addClass('avatar-default');

                            $('form').find('.image_error').parent().removeClass('has-error');
                            $('form').find('.image_error').empty().hide();
                        }
                    }
                });
            }
        });

        // кнопка "Сохранить"
        $('form').on('submit', function(event) {
            if(isValidFileSize) { return true; } else { return false; }
        });
    </script>
@stop