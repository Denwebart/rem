<div class="col-md-12">
    <div class="box">
        <div class="box-title">
            <h3>Редактировать тег</h3>
        </div>
        <div class="box-body row">
            <div class="col-md-3">
                <div class="form-group @if($errors->has('image')) has-error @endif">
                    {{ Form::label('image', 'Изображение') }}<br/>
                    @if($tag->image)
                        {{ $tag->getImage(null, ['class' => 'page-image margin-bottom-10']) }}
                        <div class="clearfix"></div>
                    @endif

                    {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs pull-left']) }}

                    @if($tag->image)
                        <a href="javascript:void(0)" id="delete-image">
                            <i class="material-icons">delete</i>
                        </a>
                        @section('script')
                            @parent

                            <script type="text/javascript">
                                $('#delete-image').click(function(){
                                    if(confirm('Вы уверены, что хотите удалить изображение?')) {
                                        $.ajax({
                                            url: '<?php echo URL::route('admin.tags.deleteImage', ['id' => $tag->id]) ?>',
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
                    @endif
                    <div class="clearfix"></div>
                    <small class="info">
                        {{ Config::get('settings.maxImageSizeInfo') }}
                    </small>
                    <small class="image_error error text-danger">
                        {{ $errors->first('image') }}
                    </small>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group @if($errors->has('title')) has-error @endif">
                    {{ Form::label('title', 'Тег') }}
                    {{ Form::text('title', $tag->title, ['class' => 'form-control', 'placeholder' => 'Новый тег']) }}
                    @if($errors->has('title'))
                        <small class="help-block">
                            {{ $errors->first('title') }}
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{ Form::hidden('backUrl', $backUrl) }}

    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
</div><!-- ./col -->

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
                $('form').find('.image_error').parent().addClass('has-error');
                $('form').find('.image_error').empty().append('Недопустимый размер файла.').show();
                isValidFileSize = false;
            } else {
                $('form').find('.image_error').parent().removeClass('has-error');
                $('form').find('.image_error').empty().hide();
                isValidFileSize = true;
            }
        });

        // кнопка "Сохранить"
        $(document).on('click', '.save-button', function() {
            $("#tagsForm").submit();
        });
        $('form').on('submit', function(event) {
            if(isValidFileSize) { return true; } else { return false; }
        });
    </script>

@stop