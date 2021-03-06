@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Редактирование информации об автомобиле' : 'Редактирование информации об автомобиле пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.userInfo')
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        @include('widgets.breadcrumbs', ['items' => [
            [
                'title' => Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login,
                'url' => URL::route('user.profile', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => (Auth::user()->is($user)) ? 'Мои автомобили' : 'Автомобили пользователя ' . $user->login,
                'url' => URL::route('user.gallery', ['login' => $user->getLoginForUrl()])
            ],
            [
                'title' => $title
            ]
        ]])

        <div class="row">
            <div class="col-md-12" id="content">

                <div class="row hidden-lg hidden-md">
                    @include('cabinet::user.userInfoMobile')
                </div>

                <h2>{{ $title }}</h2>
                <div class="well">
                    {{ Form::model($image, ['method' => 'POST', 'route' => ['user.gallery.editPhoto', 'login' => $user->getLoginForUrl(), 'id' => $image->id], 'files' => true, 'id' => 'editPhoto']) }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="button-group margin-bottom-20 pull-right">
                                    <a href="{{ URL::route('user.gallery', ['login' => $user->getLoginForUrl()]) }}" class="btn btn-primary btn-sm">
                                        <i class="material-icons">keyboard_arrow_left</i>
                                        <span class="hidden-xxs">Отмена</span>
                                    </a>
                                    {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div id="page-image" class="margin-bottom-10">
                                            {{ $image->getImage(null) }}
                                        </div>
                                        <div class="form-group @if($errors->has('image')) has-error @endif">
                                            {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary btn-sm btn-full file-inputs ajax-upload']) }}
                                            <div class="clearfix"></div>
                                            <small class="info">
                                                {{ Config::get('settings.maxImageSizeInfo') }}
                                            </small>
                                            <small class="image_error error text-danger">
                                                {{ $errors->first('image') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group @if($errors->has('title')) has-error @endif">
                                            {{ Form::label('title', 'Марка автомобиля', ['class' => 'control-label']) }}
                                            {{ Form::text('title', $image->title, ['class' => 'form-control']) }}
                                            <small class="title_error error text-danger">
                                                {{ $errors->first('title') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group @if($errors->has('description')) has-error @endif">
                                    {{ Form::label('description', 'Описание', ['class' => 'control-label']) }}
                                    {{ Form::textarea('description', $image->description, ['class' => 'form-control editor']) }}
                                    <small class="description_error error text-danger">
                                        {{ $errors->first('description') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{ Form::hidden('_token', csrf_token()) }}
                        {{ Form::hidden('tempPath', $user->getTempPath(), ['id' => 'tempPath']) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}

    <script type="text/javascript">
        tinymce.init({
            plugins: [
                "advlist lists link",
                "wordcount",
                "emoticons",
                "paste"
            ],
            menubar:false,
            toolbar1: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link emoticons",
            language: 'ru',
            selector: ".editor",
            relative_urls: false,
            remove_script_host : true,
            convert_urls : false,
            paste_text_sticky: true,
            paste_text_sticky_default: true,
            setup: function (editor) {
                editor.on('init', function() {
                    editor.getDoc().body.style.fontSize = '14px';
                    editor.getDoc().body.style.fontFamily = '"Open Sans", sans-serif';
                    editor.getDoc().body.style.lineHeight = '1.42857';
                });
            }
        });
    </script>

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();
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
                    url: '<?php echo URL::route('uploadIntoTemp', ['watermark' => 0, 'isDeleted' => false]) ?>',
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
                            $('#page-image').html(response.imageHtml);

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