@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мои автомобили' : 'Автомобили пользователя ' . $user->login) : 'Автомобиль пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
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
                    @include('cabinet::user.userInfoMobile')
                </div>

                <h2>{{ $title }}</h2>
                <div class="well">
                    <div id="list-of-images">
                        @foreach($images as $image)
                            <div class="item" data-image-id="{{ $image->id }}">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="image">
                                            <a class="fancybox" data-fancybox-group="group-gallery" href="{{ $image->getImageLink() }}">
                                                {{ $image->getImage() }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="image-description">
                                            <div class="row">
                                                @if(Auth::check())
                                                    @if((Auth::user()->is($user) && !$headerWidget->isBannedIp && !$user->is_banned) || Auth::user()->isAdmin() || Auth::user()->isModerator())
                                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                                            <h4>
                                                                {{ $image->title }}
                                                            </h4>
                                                        </div>
                                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                                            <div class="buttons pull-right">
                                                                <a href="{{ URL::route('user.gallery.editPhoto', ['login' => $user->getLoginForUrl(),'id' => $image->id]) }}" class="pull-left" title="Редактировать изображение" data-toggle="tooltip">
                                                                    <i class="material-icons">edit</i>
                                                                </a>
                                                                @if(!Auth::user()->isModerator())
                                                                    <a href="javascript:void(0)" class="delete-photo pull-left" data-id="{{ $image->id }}" title="Удалить изображение" data-toggle="tooltip">
                                                                        <i class="material-icons">delete</i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-12">
                                                            <h4>
                                                                {{ $image->title }}
                                                            </h4>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="col-md-12">
                                                        <h4>
                                                            {{ $image->title }}
                                                        </h4>
                                                    </div>
                                                @endif
                                            </div>

                                            {{ $image->description }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if(Auth::check())
                            @if(!count($images) && Auth::user()->is($user))
                                <p>
                                    Вы еще не добавили ни одной фотографии автомобиля.
                                    Вы можете добавить максимум 5 фотографий.
                                </p>
                            @elseif(!count($images))
                                <p>
                                    Фотографий нет.
                                </p>
                            @endif
                        @else
                            <p>
                                Фотографий нет.
                            </p>
                        @endif
                    </div>

                    {{--Загрузка новой фотографии--}}
                    @if(Auth::check())
                        @if(Auth::user()->is($user))
                            @if(!$headerWidget->isBannedIp)
                                @if(!$user->is_banned)
                                    @if(Config::get('settings.numberOfUserImages') > count($images))
                                        <div id="new-photo">

                                            <h3>Добавить фотографию автомобиля</h3>

                                            {{ Form::open(['method' => 'POST', 'route' => ['user.gallery.uploadPhoto', $user->getLoginForUrl()], 'files' => true], ['id' => 'uploadPhoto']) }}

                                            <div class="row">
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group @if($errors->has('image')) has-error @endif">
                                                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary btn-sm btn-full file-inputs ajax-upload']) }}
                                                        <small class="image_error error text-danger">
                                                            {{ $errors->first('image') }}
                                                        </small>
                                                        <small class="info">
                                                            {{ Config::get('settings.maxImageSizeInfo') }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-xs-11 col-md-5">
                                                    <div class="new-image"></div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-lg-12">
                                                    <div class="form-group @if($errors->has('title')) has-error @endif">
                                                        {{ Form::label('title', 'Марка автомобиля', ['class' => 'control-label']) }}
                                                        {{ Form::text('title', null, ['class' => 'form-control']) }}
                                                        <small class="title_error error text-danger">
                                                            {{ $errors->first('title') }}
                                                        </small>
                                                    </div>
                                                    <div class="form-group @if($errors->has('description')) has-error @endif">
                                                        {{ Form::label('description', 'Описание', ['class' => 'control-label']) }}
                                                        {{ Form::textarea('description', null, ['class' => 'form-control editor']) }}
                                                        <small class="description_error error text-danger">
                                                            {{ $errors->first('description') }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8 col-xs-12 col-xs-offset-0">
                                                    {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm btn-full']) }}
                                                </div>
                                            </div>

                                            {{ Form::hidden('_token', csrf_token()) }}
                                            {{ Form::hidden('tempPath', $user->getTempPath(), ['id' => 'tempPath']) }}

                                            {{ Form::close() }}
                                        </div>
                                    @else
                                        Вы уже добавили максимальное количество фотографий (5).
                                        Больше фотографий добавить нельзя.
                                    @endif
                                @else
                                    @include('cabinet::user.banMessage')
                                @endif
                            @else
                                @include('messages.bannedIp')
                            @endif
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
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

    <!-- FancyBox2 -->
    {{-- стили в fonts.css --}}
    {{--<link rel="stylesheet" href="/fancybox/jquery.fancybox.min.css?v=2.1.5" type="text/css" media="screen" />--}}
    {{HTML::script('fancybox/jquery.fancybox.pack.min.js?v=2.1.5')}}
    <script type="text/javascript">
        $(document).ready(function() {
            $(".fancybox").fancybox();
        });
    </script>

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();
    </script>

    <!-- Delete Photo -->
    @if(Auth::check())
        @if(Auth::user()->is($user) || Auth::user()->isAdmin())
            <script type="text/javascript">
                $('.delete-photo').click(function(){
                    var imageId = $(this).data('id');
                    if(confirm('Вы уверены, что хотите удалить фотографию?')) {
                        $.ajax({
                            url: '<?php echo URL::route('user.gallery.deletePhoto', ['login' => $user->getLoginForUrl()]) ?>',
                            dataType: "text json",
                            type: "POST",
                            data: {imageId: imageId},
                            beforeSend: function(request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
                            success: function(response) {
                                if(response.success){
                                    $('#site-messages').prepend(response.message);
                                    setTimeout(function() {
                                        hideSiteMessage($('.site-message'));
                                    }, 2000);

                                    $('[data-image-id=' + imageId + ']').remove();
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endif

    <!-- Загрузка изображения ajax -->
    <script type="text/javascript">
        $('.ajax-upload').on('change', function () {
            if (this.files[0].size > 5242880) {
                $('form').find('.image_error').parent().addClass('has-error');
                $('form').find('.image_error').empty().append('Недопустимый размер файла.').show();
            } else {
                var fileData = new FormData();
                fileData.append('image', $(this)[0].files[0]);
                fileData.append('tempPath', $('#tempPath').val());
                fileData.append('class', ' avatar');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo URL::route('uploadIntoTemp', ['watermark' => 0]) ?>',
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
                            $('.new-image').html(response.imageHtml);

                            $('form').find('.image_error').parent().removeClass('has-error');
                            $('form').find('.image_error').empty().hide();
                        }
                    }
                });
            }
        });

        <!-- Удаление временного изображения ajax -->
        $('.new-image').on('click', '#delete-temp-image', function(){
            var $button = $(this);
            if(confirm('Вы уверены, что хотите удалить изображение?')) {
                var imageName = $('.file-input-name');
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

                            $('.new-image').html('');
                            imageName.text('');
                            $('.ajax-upload').val('');

                            $('form').find('.image_error').parent().removeClass('has-error');
                            $('form').find('.image_error').empty().hide();
                        }
                    }
                });
            }
        });
    </script>
@stop