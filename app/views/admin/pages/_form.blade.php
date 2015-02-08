<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Основная информация</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('parent_id', 'Родитель', ['class' => 'control-label']) }}
                {{ Form::select('parent_id', Page::getContainer(), $page->parent_id, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('alias', 'Алиас') }}
                {{ Form::text('alias', $page->alias, ['class' => 'form-control']) }}
                {{ $errors->first('alias') }}
            </div>
            <div class="form-group">
                {{ Form::label('title', 'Заголовок') }}
                {{ Form::text('title', $page->title, ['class' => 'form-control']) }}
                {{ $errors->first('title') }}
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('menu_title', 'Заголовок меню') }}
                        {{ Form::text('menu_title', $page->menu_title, ['class' => 'form-control']) }}
                        {{ $errors->first('menu_title') }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::label('is_container', 'Содержит подпункты') }}
                        {{ Form::hidden('is_container', 0) }}
                        {{ Form::checkbox('is_container', 1) }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::label('show_submenu', 'Показывать подменю') }}
                        {{ Form::hidden('show_submenu', 0) }}
                        {{ Form::checkbox('show_submenu', 1) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('image', 'Изображение') }}<br/>
                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                        {{ $errors->first('image') }}
                        @if($page->image_alt)
                            <img src="" alt=""/>
                            {{ HTML::image($page->image_alt) }}
                        @endif
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('image_alt', 'Альт к изображению') }}
                        {{ Form::textarea('image_alt', $page->image_alt, ['class' => 'form-control', 'rows' => 4]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-5">
    <div class="box">
        <div class="box-title">
            <h3>Мета-теги SEO</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('meta_title', 'Мета-тег Title') }}
                {{ Form::textarea('meta_title', $page->meta_title, ['class' => 'form-control', 'rows' => 2]) }}
                {{ $errors->first('meta_title') }}
            </div>
            <div class="form-group">
                {{ Form::label('meta_desc', 'Мета-тег Description') }}
                {{ Form::textarea('meta_desc', $page->meta_desc, ['class' => 'form-control', 'rows' => 3]) }}
                {{ $errors->first('meta_desc') }}
            </div>
            <div class="form-group">
                {{ Form::label('meta_key', 'Мета-тег Keywords') }}
                {{ Form::textarea('meta_key', $page->meta_key, ['class' => 'form-control', 'rows' => 3]) }}
                {{ $errors->first('meta_key') }}
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-title">
            <h3>Дата публикации</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('is_published', 'Опубликован') }}
                        {{ Form::hidden('is_published', 0) }}
                        {{ Form::checkbox('is_published', 1) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('published_at', 'Дата публикации') }}
                        {{ Form::text('published_at', $page->published_at, ['class' => 'form-control datepicker-input']) }}
                        {{ $errors->first('published_at') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="box">
        <div class="box-title">
            <h3>Контент страницы</h3>
        </div>
        <div class="box-body">
            <div class="box">
                <div class="box-title">
                    {{ Form::label('introtext', 'Краткое описание') }}
                    <div class="pull-right box-toolbar">
                        <a href="#" class="btn btn-link btn-xs collapse-box"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="box-body no-padding" style="display: none">
                    <div class="form-group">
                        {{ Form::textarea('introtext', $page->introtext, ['class' => 'form-control editor']) }}
                        {{ $errors->first('introtext') }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                {{ Form::label('content', 'Контент') }}
                {{ Form::textarea('content', $page->content, ['class' => 'form-control editor']) }}
                {{ $errors->first('content') }}
            </div>

            {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
            <a href="{{ URL::route('admin.pages.index') }}" class="btn btn-primary">Отмена</a>
        </div>
    </div>
</div>

@section('style')
    <link rel="stylesheet" href="/backend/css/datepicker/datepicker.css" />
@stop

@section('script')
    <!-- Forms -->
    <script src="/backend/js/plugins/bootstrapValidator/bootstrapValidator.min.js" type="text/javascript"></script>

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();
    </script>

    <!-- Date picker -->
    <script src="/backend/js/plugins/datepicker/datepicker.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.datepicker-input').datepicker();
    </script>

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replaceAll('editor')
    </script>

    <!-- iCheck -->
    <script src="/backend/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("input[type='checkbox'], input[type='radio']").iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal'
        });
    </script>

    {{--<script type="text/javascript">--}}
        {{--$(document).ready(function() {--}}
            {{--$('#registerForm').bootstrapValidator({--}}
                {{--message: 'This value is not valid',--}}
                {{--fields: {--}}
                    {{--username: {--}}
                        {{--message: 'The username is not valid',--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'The username is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--stringLength: {--}}
                                {{--min: 6,--}}
                                {{--max: 30,--}}
                                {{--message: 'The username must be more than 6 and less than 30 characters long'--}}
                            {{--},--}}
                            {{--regexp: {--}}
                                {{--regexp: /^[a-zA-Z0-9_\.]+$/,--}}
                                {{--message: 'The username can only consist of alphabetical, number, dot and underscore'--}}
                            {{--},--}}
                            {{--different: {--}}
                                {{--field: 'password',--}}
                                {{--message: 'The username and password can\'t be the same as each other'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--email: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'The email address is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--emailAddress: {--}}
                                {{--message: 'The input is not a valid email address'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--password: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'The password is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--identical: {--}}
                                {{--field: 'confirmPassword',--}}
                                {{--message: 'The password and its confirm are not the same'--}}
                            {{--},--}}
                            {{--different: {--}}
                                {{--field: 'username',--}}
                                {{--message: 'The password can\'t be the same as username'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--confirmPassword: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'The confirm password is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--identical: {--}}
                                {{--field: 'password',--}}
                                {{--message: 'The password and its confirm are not the same'--}}
                            {{--},--}}
                            {{--different: {--}}
                                {{--field: 'username',--}}
                                {{--message: 'The password can\'t be the same as username'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--phoneNumber: {--}}
                        {{--validators: {--}}
                            {{--digits: {--}}
                                {{--message: 'The value can contain only digits'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}

            {{--$('#contactForm').bootstrapValidator({--}}
                {{--message: 'This value is not valid',--}}
                {{--fields: {--}}
                    {{--name: {--}}
                        {{--message: 'Name is not valid',--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'Name is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--regexp: {--}}
                                {{--regexp: /^[a-zA-Z0-9_\.]+$/,--}}
                                {{--message: 'Name can only consist of alphabetical, number, dot and underscore'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--email: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'The email address is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--emailAddress: {--}}
                                {{--message: 'The input is not a valid email address'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--website: {--}}
                        {{--validators: {--}}
                            {{--uri: {--}}
                                {{--message: 'The input is not a valid URL'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--Contactmessage: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'Message is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--stringLength: {--}}
                                {{--min: 6,--}}
                                {{--message: 'Message must be more than 6 characters long'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--captcha: {--}}
                        {{--validators: {--}}
                            {{--callback: {--}}
                                {{--message: 'Wrong answer',--}}
                                {{--callback: function(value, validator) {--}}
                                    {{--var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);--}}
                                    {{--return value == sum;--}}
                                {{--}--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}


            {{--$('#ExpressionValidator').bootstrapValidator({--}}
                {{--message: 'This value is not valid',--}}
                {{--fields: {--}}
                    {{--email: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'The email address is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--emailAddress: {--}}
                                {{--message: 'The input is not a valid email address'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--website: {--}}
                        {{--validators: {--}}
                            {{--uri: {--}}
                                {{--message: 'The input is not a valid URL'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--phoneNumber: {--}}
                        {{--validators: {--}}
                            {{--digits: {--}}
                                {{--message: 'The value can contain only digits'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--color: {--}}
                        {{--validators: {--}}
                            {{--hexColor: {--}}
                                {{--message: 'The input is not a valid hex color'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--zipCode: {--}}
                        {{--validators: {--}}
                            {{--usZipCode: {--}}
                                {{--message: 'The input is not a valid US zip code'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}

            {{--$('#IdenticalValidator').bootstrapValidator({--}}
                {{--message: 'This value is not valid',--}}
                {{--fields: {--}}
                    {{--password: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'The password is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--identical: {--}}
                                {{--field: 'confirmPassword',--}}
                                {{--message: 'The password and its confirm are not the same'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    {{--confirmPassword: {--}}
                        {{--validators: {--}}
                            {{--notEmpty: {--}}
                                {{--message: 'The confirm password is required and can\'t be empty'--}}
                            {{--},--}}
                            {{--identical: {--}}
                                {{--field: 'password',--}}
                                {{--message: 'The password and its confirm are not the same'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}

            {{--$('#OtherValidator').bootstrapValidator({--}}
                {{--message: 'This value is not valid',--}}
                {{--fields: {--}}
                    {{--ages: {--}}
                        {{--validators: {--}}
                            {{--lessThan: {--}}
                                {{--value: 100,--}}
                                {{--inclusive: true,--}}
                                {{--message: 'The ages has to be less than 100'--}}
                            {{--},--}}
                            {{--greaterThan: {--}}
                                {{--value: 10,--}}
                                {{--inclusive: false,--}}
                                {{--message: 'The ages has to be greater than or equals to 10'--}}
                            {{--}--}}
                        {{--}--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}
@stop