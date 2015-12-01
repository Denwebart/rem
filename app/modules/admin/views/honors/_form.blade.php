<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Основная информация</h3>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('title')) has-error @endif">
                {{ Form::label('title', 'Название', ['class' => 'control-label']) }}
                {{ Form::text('title', $honor->title, ['class' => 'form-control']) }}
                @if($errors->has('title'))
                    <small class="help-block">
                        {{ $errors->first('title') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('image')) has-error @endif">
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('image', 'Изображение', ['class' => 'control-label']) }}<br/>
                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}

                        <small class="info">
                            {{ Config::get('settings.maxImageSizeInfo') }}
                        </small>

                        <small class="image_error error text-danger">
                            {{ $errors->first('image') }}
                        </small>
                    </div>
                    <div class="col-sm-6">
                        @if($honor->image)
                            {{ $honor->getImage(null, ['class' => 'page-image']) }}

                            <a href="javascript:void(0)" id="delete-image">Удалить</a>
                            @section('script')
                                @parent

                                <script type="text/javascript">
                                    $('#delete-image').click(function(){
                                        if(confirm('Вы уверены, что хотите удалить изображение?')) {
                                            $.ajax({
                                                url: '<?php echo URL::route('admin.honors.deleteImage', ['id' => $honor->id]) ?>',
                                                dataType: "text json",
                                                type: "POST",
                                                data: {field: 'image'},
                                                beforeSend: function(request) {
                                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                                },
                                                success: function(response) {
                                                    if(response.success){
                                                        $('#delete-image').css('display', 'none');
                                                        $('.page-image').remove();
                                                    }
                                                }
                                            });
                                        }
                                    });
                                </script>
                            @stop
                        @else
                            {{ $honor->getImage() }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group @if($errors->has('description')) has-error @endif">
                {{ Form::textarea('description', $honor->description, ['class' => 'form-control editor']) }}
                @if($errors->has('description'))
                    <small class="help-block">
                        {{ $errors->first('description') }}
                    </small>
                @endif
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
            <div class="form-group @if($errors->has('meta_title')) has-error @endif">
                {{ Form::label('meta_title', 'Мета-тег Title') }}
                {{ Form::textarea('meta_title', $honor->meta_title, ['class' => 'form-control', 'rows' => 4]) }}
                @if($errors->has('meta_title'))
                    <small class="help-block">
                        {{ $errors->first('meta_title') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('meta_desc')) has-error @endif">
                {{ Form::label('meta_desc', 'Мета-тег Description') }}
                {{ Form::textarea('meta_desc', $honor->meta_desc, ['class' => 'form-control', 'rows' => 5]) }}
                @if($errors->has('meta_desc'))
                    <small class="help-block">
                        {{ $errors->first('meta_desc') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('meta_key')) has-error @endif">
                {{ Form::label('meta_key', 'Мета-тег Keywords') }}
                {{ Form::textarea('meta_key', $honor->meta_key, ['class' => 'form-control', 'rows' => 5]) }}
                @if($errors->has('meta_key'))
                    <small class="help-block">
                        {{ $errors->first('meta_key') }}
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <!-- TinyMCE image -->
    {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
    {{ Form::hidden('tempPath', $honor->getTempPath(), ['id' => 'tempPath']) }}

    {{ Form::hidden('backUrl', $backUrl) }}

    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
</div>

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('admin::tinymce-init')
@stop

@section('script')
    @parent

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();

        var isValidFileSize = true;
        $(".file-inputs").on("change", function(){
            var file = this.files[0];
            if (file.size > 5242880) {
                $('form').find('.image_error').parent().parent().parent().addClass('has-error');
                $('form').find('.image_error').empty().append('Недопустимый размер файла.').show();
                isValidFileSize = false;
            } else {
                $('form').find('.image_error').parent().parent().parent().removeClass('has-error');
                $('form').find('.image_error').empty().hide();
                isValidFileSize = true;
            }
        });

        // кнопка "Сохранить"
        $(document).on('click', '.save-button', function() {
            $("#honorsForm").submit();
        });
        $('form').on('submit', function(event) {
            if(isValidFileSize) { return true; } else { return false; }
        });
    </script>

@stop