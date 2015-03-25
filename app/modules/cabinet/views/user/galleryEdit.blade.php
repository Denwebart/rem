@extends('cabinet::layouts.cabinet')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li><a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">Мой профиль</a></li>
                <li><a href="{{ URL::route('user.gallery', ['login' => $user->login]) }}">Мой автомобиль</a></li>
                <li>Редактирование информации о фотографии</li>
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

            {{--Загрузка новой фотографии--}}
            <div id="new-photo">

                <h3>Редактирование информации о фотографии</h3>

                {{ Form::model($image, ['method' => 'POST', 'route' => ['user.gallery.editPhoto', 'login' => $user->login, 'id' => $image->id], 'files' => true], ['id' => 'editPhoto']) }}

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            {{ Html::image($image->getImageUrl(), $image->desctiption, ['class' => 'img-responsive']) }}
                            {{ Form::file('image', ['title' => 'Загрузить изображения', 'class' => 'btn btn-primary file-inputs']) }}
                            {{ $errors->first('avatar') }}
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            {{ Form::label('title', 'Заголовок изображения') }}
                            {{ Form::text('title', $image->title, ['class' => 'form-control']) }}
                            {{ $errors->first('title') }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('description', 'Описание изображения') }}
                            {{ Form::textarea('description', $image->description, ['class' => 'form-control']) }}
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

@stop