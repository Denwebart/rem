<div class="col-md-7">
    <div class="box">
        <div class="box-title" style="padding: 7px 10px">
            <h3>Основная информация</h3>
            <div class="pull-right author">
                {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-right']) }}
                <span class="pull-right">
                    {{ $page->user->login }}
                </span>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('alias')) has-error @endif">
                {{ Form::label('alias', 'Алиас') }}
                {{ Form::text('alias', $page->alias, ['class' => 'form-control']) }}
                @if($errors->has('alias'))
                    <small class="help-block">
                        {{ $errors->first('alias') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('title')) has-error @endif">
                {{ Form::label('title', 'Заголовок') }}
                {{ Form::text('title', $page->title, ['class' => 'form-control']) }}
                @if($errors->has('title'))
                    <small class="help-block">
                        {{ $errors->first('title') }}
                    </small>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-6">
                    <div class="form-group display-inline-block @if($errors->has('image')) has-error @endif">
                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs pull-left']) }}

                        @if($page->image)
                            <a href="javascript:void(0)" id="delete-image" title="Удалить изображение" data-toggle="tooltip">
                                <i class="fa fa-trash"></i>
                            </a>
                        @section('script')
                            @parent

                            <script type="text/javascript">
                                $('#delete-image').click(function(){
                                    var $deleteButton = $(this);
                                    if(confirm('Вы уверены, что хотите удалить изображение?')) {
                                        $.ajax({
                                            url: '<?php echo URL::route('admin.pages.deleteImage', ['id' => $page->id]) ?>',
                                            dataType: "text json",
                                            type: "POST",
                                            data: {field: 'image'},
                                            beforeSend: function(request) {
                                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                            },
                                            success: function(response) {
                                                if(response.success){
                                                    $deleteButton.css('display', 'none');
                                                    $deleteButton.nextAll('.tooltip:first').remove();
                                                    $('.page-image').remove();
                                                }
                                            }
                                        });
                                    }
                                });
                            </script>
                        @stop
                        @endif

                        @if($errors->has('image'))
                            <small class="help-block">
                                {{ $errors->first('image') }}
                            </small>
                        @endif

                        @if($page->image)
                            <div class="clearfix"></div>
                            {{ $page->getImage(null, ['class' => 'page-image margin-top-10']) }}
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-6">
                    <div class="form-group @if($errors->has('image_alt')) has-error @endif">
                        {{ Form::label('image_alt', 'Альт к изображению') }}
                        {{ Form::textarea('image_alt', $page->image_alt, ['class' => 'form-control', 'rows' => 4]) }}
                        @if($errors->has('image_alt'))
                            <small class="help-block">
                                {{ $errors->first('image_alt') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin::pages._addRelated', ['page' => $page])

    @include('admin::pages._addTags', ['page' => $page])
</div>

<div class="col-md-5">
    <div class="box">
        <div class="box-title">
            <h3>Мета-теги SEO</h3>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('meta_title')) has-error @endif">
                {{ Form::label('meta_title', 'Мета-тег Title') }}
                {{ Form::textarea('meta_title', $page->meta_title, ['class' => 'form-control', 'rows' => 4]) }}
                @if($errors->has('meta_title'))
                    <small class="help-block">
                        {{ $errors->first('meta_title') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('meta_desc')) has-error @endif">
                {{ Form::label('meta_desc', 'Мета-тег Description') }}
                {{ Form::textarea('meta_desc', $page->meta_desc, ['class' => 'form-control', 'rows' => 5]) }}
                @if($errors->has('meta_desc'))
                    <small class="help-block">
                        {{ $errors->first('meta_desc') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('meta_key')) has-error @endif">
                {{ Form::label('meta_key', 'Мета-тег Keywords') }}
                {{ Form::textarea('meta_key', $page->meta_key, ['class' => 'form-control', 'rows' => 5]) }}
                @if($errors->has('meta_key'))
                    <small class="help-block">
                        {{ $errors->first('meta_key') }}
                    </small>
                @endif
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-title">
            <h3>Дата публикации</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-5 col-md-12 col-sm-6">
                    <div class="form-group margin-top-25 md-margin-top-0 xs-margin-top-0">
                        {{ Form::label('is_published', 'Опубликован') }}
                        {{ Form::hidden('is_published', 0, ['id' => 'is_published_uncheck']) }}
                        {{ Form::checkbox('is_published', 1) }}
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 col-sm-6">
                    <div class="form-group @if($errors->has('published_at')) has-error @endif">
                        {{ Form::label('published_at', 'Дата публикации') }}

                        <div class="input-group">
                            {{ Form::text('published_at',
                                !is_null($page->published_at) ? date('d-m-Y', strtotime($page->published_at)) : '',
                                ['class' => 'form-control datepicker-input'])
                            }}
                            <span id="published_at_time" class="input-group-addon">
                            {{ Form::hidden('publishedTime', !is_null($page->published_at) ? date('H:i:s', strtotime($page->published_at)) : Config::get('settings.defaultPublishedTime'), ['id' => 'publishedTime'])}}
                                {{ !is_null($page->published_at) ? date('H:i:s', strtotime($page->published_at)) : '' }}
                        </span>
                        </div>
                        @if($errors->has('published_at'))
                            <small class="help-block">
                                {{ $errors->first('published_at') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="box">
        <div class="box-title">
            <h3>Текст статьи</h3>
        </div>
        <div class="box-body">

            <div class="form-group @if($errors->has('content')) has-error @endif">
                {{ Form::textarea('content', $page->content, ['class' => 'form-control editor']) }}
                @if($errors->has('content'))
                    <small class="help-block">
                        {{ $errors->first('content') }}
                    </small>
                @endif
            </div>

            <!-- TinyMCE image -->
            {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
            {{ Form::hidden('tempPath', $page->getTempPath(), ['id' => 'tempPath']) }}

            {{ Form::hidden('backUrl', $backUrl) }}

            {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
            <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
        </div>
    </div>
</div>

@section('style')
    @parent
    <link rel="stylesheet" href="/backend/css/datepicker/datepicker.css" />

    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('admin::tinymce-init', ['imagePath' => $page->getImageEditorPath()])
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

    <!-- Date picker -->
    <script src="/backend/js/plugins/datepicker/datepicker.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.datepicker-input').datepicker({
            format: "dd-mm-yyyy"
        }).on('changeDate', function(ev){
            $("#published_at_time").text("<?php echo Config::get('settings.defaultPublishedTime')?>");
        });
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('#is_published').on('ifUnchecked', function(event){
                $('#published_at').val('');
                $('#published_at_time').text('');
            });
            $('#published_at').on('change', function(){
                if(this.value == ''){
                    $('#published_at_time').text('');
                }
            });
            $('#published_at').datepicker().on('changeDate', function(ev){
                $('#is_published').iCheck('check');
            });
        });
    </script>

@stop