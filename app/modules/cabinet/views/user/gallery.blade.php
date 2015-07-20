@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мой автомобиль' : 'Автомобиль пользователя ' . $user->login) : 'Автомобиль пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    {{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login }}
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">
                <h2>{{ $title }}</h2>

                @if(Auth::check())
                    @if((Auth::user()->is($user) && !$headerWidget->isBannedIp && !$user->is_banned) || Auth::user()->isAdmin())
                        <div id="lists-of-images">
                            @foreach($images as $image)
                                <div class="item row" data-image-id="{{ $image->id }}">
                                    <div class="col-md-5">
                                        {{ $image->getImage() }}
                                    </div>
                                    <div class="col-md-7">
                                        <a href="javascript:void(0)" class="btn btn-danger delete-photo" data-id="{{ $image->id }}">Удалить</a>
                                        <a href="{{ URL::route('user.gallery.editPhoto', ['login' => $user->getLoginForUrl(),'id' => $image->id]) }}" class="btn btn-info">Редактировать</a>
                                        <h3>{{ $image->title }}</h3>
                                        {{ $image->description }}
                                    </div>
                                </div>
                            @endforeach
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
                        </div>
                    @else
                        @include('cabinet::user.galleryForNotAuthorized')
                    @endif

                    {{--Загрузка новой фотографии--}}
                    @if(Auth::user()->is($user))
                        @if(!$headerWidget->isBannedIp)
                            @if(!$user->is_banned)
                                @if(Config::get('settings.numberOfUserImages') > count($images))
                                    <div id="new-photo">

                                        <h3>Добавить фотографию</h3>

                                        {{ Form::open(['method' => 'POST', 'route' => ['user.gallery.uploadPhoto', $user->getLoginForUrl()], 'files' => true], ['id' => 'uploadPhoto']) }}

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    {{ Form::file('image', ['title' => 'Загрузить изображения', 'class' => 'btn btn-primary file-inputs']) }}
                                                    {{ $errors->first('image') }}
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                    {{ Form::label('title', 'Заголовок изображения') }}
                                                    {{ Form::text('title', null, ['class' => 'form-control']) }}
                                                    {{ $errors->first('title') }}
                                                </div>

                                                <div class="form-group">
                                                    {{ Form::label('description', 'Описание изображения') }}
                                                    {{ Form::textarea('description', null, ['class' => 'form-control']) }}
                                                    {{ $errors->first('description') }}
                                                </div>

                                                <div class="button-group">
                                                    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
                                                </div>
                                            </div>
                                        </div>
                                        {{ Form::hidden('_token', csrf_token()) }}
                                        {{ Form::close() }}
                                    </div>
                                @else
                                    Больше фотографий добавить нельзя :(
                                @endif
                            @else
                                @include('cabinet::user.banMessage')
                            @endif
                        @else
                            @include('messages.bannedIp')
                        @endif
                    @endif
                @else
                    @include('cabinet::user.galleryForNotAuthorized')
                @endif
            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replace('description', {
            toolbar: [
                [ 'Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo' ],
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
                { name: 'links', items: [ 'Link', 'Unlink'] },
                { name: 'smiley', items: ['Smiley']}
            ]
        })
    </script>

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
                                    $('[data-image-id=' + imageId + ']').remove();
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endif
@stop