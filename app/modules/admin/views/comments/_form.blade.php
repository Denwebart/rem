<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Основная информация</h3>
        </div>
        <div class="box-body">
            {{--<div class="form-group">--}}
                {{--{{ Form::label('parent_id', 'Родитель', ['class' => 'control-label']) }}--}}
                {{--{{ Form::select('parent_id', Page::getContainer(), $page->parent_id, ['class' => 'form-control']) }}--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::label('alias', 'Алиас') }}--}}
                {{--{{ Form::text('alias', $page->alias, ['class' => 'form-control']) }}--}}
                {{--{{ $errors->first('alias') }}--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::label('title', 'Заголовок') }}--}}
                {{--{{ Form::text('title', $page->title, ['class' => 'form-control']) }}--}}
                {{--{{ $errors->first('title') }}--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-sm-6">--}}
                        {{--{{ Form::label('menu_title', 'Заголовок меню') }}--}}
                        {{--{{ Form::text('menu_title', $page->menu_title, ['class' => 'form-control']) }}--}}
                        {{--{{ $errors->first('menu_title') }}--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-3">--}}
                        {{--{{ Form::label('is_container', 'Содержит подпункты') }}--}}
                        {{--{{ Form::hidden('is_container', 0) }}--}}
                        {{--{{ Form::checkbox('is_container', 1) }}--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-3">--}}
                        {{--{{ Form::label('show_submenu', 'Показывать подменю') }}--}}
                        {{--{{ Form::hidden('show_submenu', 0) }}--}}
                        {{--{{ Form::checkbox('show_submenu', 1) }}--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-sm-6">--}}
                        {{--{{ Form::label('image', 'Изображение') }}<br/>--}}
                        {{--{{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}--}}
                        {{--{{ $errors->first('image') }}--}}
                        {{--@if($page->image_alt)--}}
                            {{--<img src="" alt=""/>--}}
                            {{--{{ HTML::image($page->image_alt) }}--}}
                        {{--@endif--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-6">--}}
                        {{--{{ Form::label('image_alt', 'Альт к изображению') }}--}}
                        {{--{{ Form::textarea('image_alt', $page->image_alt, ['class' => 'form-control', 'rows' => 4]) }}--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
</div>

<div class="col-md-5">

    <div class="box">
        <div class="box-title">
            <h3>Дата публикации</h3>
        </div>
        <div class="box-body">
            {{--<div class="form-group">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-sm-6">--}}
                        {{--{{ Form::label('is_published', 'Опубликован') }}--}}
                        {{--{{ Form::hidden('is_published', 0, ['id' => 'is_published_uncheck']) }}--}}
                        {{--{{ Form::checkbox('is_published', 1) }}--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-6">--}}
                        {{--{{ Form::label('published_at', 'Дата публикации') }}--}}

                        {{--<div class="input-group">--}}
                            {{--{{ Form::text('published_at',--}}
                                {{--('0000-00-00 00:00:00' != $page->published_at) ? date('d-m-Y', strtotime($page->published_at)) : '',--}}
                                {{--['class' => 'form-control datepicker-input'])--}}
                            {{--}}--}}
                            {{--<span id="published_at_time" class="input-group-addon">--}}
                                {{--{{ Form::hidden('publishedTime', ('0000-00-00 00:00:00' != $page->published_at) ? date('H:i:s', strtotime($page->published_at)) : Config::get('settings.defaultPublishedTime'), ['id' => 'publishedTime'])}}--}}
                                {{--{{ ('0000-00-00 00:00:00' != $page->published_at) ? date('H:i:s', strtotime($page->published_at)) : '' }}--}}
                            {{--</span>--}}
                        {{--</div>--}}

                        {{--{{ $errors->first('published_at') }}--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
</div>

@section('script')

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replaceAll('editor')
    </script>

    <!-- iCheck -->
    <script src="/backend/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type='checkbox'], input[type='radio']").iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });
        });
    </script>

@stop