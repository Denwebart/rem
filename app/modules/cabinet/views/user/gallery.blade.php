@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li>Мой автомобиль</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                @if($user->avatar)
                    {{ HTML::image('/uploads/' . $user->login . '/' . $user->avatar, $user->login, ['class' => 'img-responsive']) }}
                @else
                    {{ HTML::image(Config::get('settings.defaultAvatar'), $user->login, ['class' => 'img-responsive avatar-default']) }}
                @endif
            </div>
        </div>
        <div class="col-lg-9">
            <h2>Мой автомобиль</h2>

            <div id="lists-of-images">
                @foreach($user->images as $image)
                    <div class="item row" data-image-id="{{ $image->id }}">
                        <div class="col-md-5">
                            {{ Html::image($image->getImageUrl(), $image->desctiption, ['class' => 'img-responsive']) }}
                        </div>
                        <div class="col-md-7">
                            <a href="javascript:void(0)" class="btn btn-danger delete-photo" data-id="{{ $image->id }}">Удалить</a>
                            <a href="{{ URL::route('user.gallery.editPhoto', ['login' => $user->login,'id' => $image->id]) }}" class="btn btn-info">Редактировать</a>
                            <h3>{{ $image->title }}</h3>
                            {{ $image->description }}
                        </div>
                    </div>
                @endforeach
            </div>



            {{--<div id="carousel-users-images" class="carousel slide" data-ride="carousel">--}}

                {{--<!-- Карусель -->--}}
                {{--<div class="carousel-inner" role="listbox">--}}

                    {{--@foreach($user->images as $key => $image)--}}

                        {{--<div class="item{{ (0 == $key) ? ' active': '' }}">--}}
                            {{--{{ Html::image($image->getImageUrl()) }}--}}
                            {{--<div class="carousel-caption">--}}
                                {{--<h3>{{ $image->title }}</h3>--}}
                                {{--{{ $image->desctiption }}--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    {{--@endforeach--}}

                {{--</div>--}}

                {{--<!-- Controls -->--}}
                {{--<a class="left carousel-control" href="#carousel-users-images" role="button" data-slide="prev">--}}
                    {{--<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>--}}
                    {{--<span class="sr-only">Previous</span>--}}
                {{--</a>--}}
                {{--<a class="right carousel-control" href="#carousel-users-images" role="button" data-slide="next">--}}
                    {{--<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>--}}
                    {{--<span class="sr-only">Next</span>--}}
                {{--</a>--}}

                {{--<!-- Controls -->--}}
                {{--<div style="text-align: center; margin-top: 10px">--}}
                    {{--@foreach($user->images as $key => $image)--}}
                        {{--<a href="javascript:void(0)" data-target="#carousel-users-images" data-slide-to="{{ $key }}" class="{{ (0 == $key) ? ' active': '' }}">--}}
                            {{--{{ Html::image($image->getImageUrl(), $image->description, ['style' => 'width: 100px']) }}--}}
                        {{--</a>--}}
                    {{--@endforeach--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--Загрузка новой фотографии--}}
            <div id="new-photo">

                <h3>Добавить фотографию</h3>

                {{--<a href="" class="btn btn-default btn-lg">--}}
                    {{--<span class="glyphicon glyphicon-plus"></span>--}}
                {{--</a>--}}

                {{ Form::open(['method' => 'POST', 'route' => ['user.gallery.uploadPhoto', $user->login], 'files' => true], ['id' => 'uploadPhoto']) }}

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            {{ Form::file('image', ['title' => 'Загрузить изображения', 'class' => 'btn btn-primary file-inputs']) }}
                            {{ $errors->first('avatar') }}
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

                {{ Form::close() }}

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
            ]
        })
    </script>

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();
    </script>

    <!-- Delete Photo -->
    <script type="text/javascript">
        $('.delete-photo').click(function(){
            var imageId = $(this).data('id');
            if(confirm('Вы уверены, что хотите удалить фотографию?')) {
                $.ajax({
                    url: '<?php echo URL::route('user.gallery.deletePhoto', ['login' => $user->login]) ?>',
                    dataType: "text json",
                    type: "POST",
                    data: {imageId: imageId},
                    success: function(response) {
                        if(response.success){
                            $('[data-image-id=' + imageId + ']').remove();
                        }
                    }
                });
            }
        });
    </script>
@stop