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
                <div class="well">
                    <div id="list-of-images">
                        @foreach($images as $image)
                            <div class="item" data-image-id="{{ $image->id }}">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="image">
                                            <a class="fancybox" rel="group-gallery" href="{{ $image->getImageLink() }}">
                                                {{ $image->getImage() }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="image-description">
                                            <div class="row">
                                                @if(Auth::check())
                                                    @if((Auth::user()->is($user) && !$headerWidget->isBannedIp && !$user->is_banned) || Auth::user()->isAdmin())
                                                        <div class="col-md-8">
                                                            <h4>
                                                                {{ $image->title }}
                                                            </h4>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="buttons pull-right">
                                                                <a href="{{ URL::route('user.gallery.editPhoto', ['login' => $user->getLoginForUrl(),'id' => $image->id]) }}" class="pull-left" title="Редактировать изображение" data-toggle="tooltip">
                                                                    <i class="material-icons">edit</i>
                                                                </a>
                                                                <a href="javascript:void(0)" class="delete-photo pull-left" data-id="{{ $image->id }}" title="Удалить изображение" data-toggle="tooltip">
                                                                    <i class="material-icons">delete</i>
                                                                </a>
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

                                            <h3>Добавить фотографию</h3>

                                            {{ Form::open(['method' => 'POST', 'route' => ['user.gallery.uploadPhoto', $user->getLoginForUrl()], 'files' => true], ['id' => 'uploadPhoto']) }}

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary btn-sm file-inputs']) }}
                                                        {{ $errors->first('image') }}
                                                    </div>
                                                    <div class="form-group">
                                                        {{ Form::label('title', 'Заголовок изображения') }}
                                                        {{ Form::text('title', null, ['class' => 'form-control']) }}
                                                        {{ $errors->first('title') }}
                                                    </div>
                                                    <div class="form-group">
                                                        {{ Form::label('description', 'Описание изображения') }}
                                                        {{ Form::textarea('description', null, ['class' => 'form-control editor']) }}
                                                        {{ $errors->first('description') }}
                                                    </div>
                                                    <div class="button-group">
                                                        {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- TinyMCE image -->
                                            {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}

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
                    @endif
                </div>
            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop

@section('style')
    @parent

    <!-- FancyBox2 -->
    <link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}

    <script type="text/javascript">
        tinymce.init({
            plugins: [
                "advlist lists link",
                "wordcount",
                "emoticons"
            ],
            menubar:false,
            toolbar1: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link emoticons",
            language: 'ru',
            selector: ".editor"
        });
    </script>

@endsection

@section('script')
    @parent

    <!-- FancyBox2 -->
    {{HTML::script('fancybox/jquery.fancybox.pack.js?v=2.1.5')}}
    <script type="text/javascript">
        $(document).ready(function() {
            $(".fancybox").fancybox();
        });
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
                                    $('#site-messages').prepend(response.message);
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