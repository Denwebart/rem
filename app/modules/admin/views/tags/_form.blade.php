<div class="col-md-12">
    <div class="box">
        <div class="box-title">
            <h3>Редактировать тег</h3>
        </div>
        <div class="box-body row">
            <div class="col-md-3">
                <div class="form-group @if($errors->has('image')) has-error @endif">
                    {{ Form::label('image', 'Изображение') }}<br/>
                    {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                    <small class="image_error error help-block">
                        {{ $errors->first('image') }}
                    </small>
                    <small class="info">
                        {{ Config::get('settings.maxImageSizeInfo') }}
                    </small>

                    @if($tag->image)
                        {{ $tag->getImage(null, ['class' => 'page-image']) }}

                        <a href="javascript:void(0)" id="delete-image">Удалить</a>
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

        $(".file-inputs").on("change", function(){
            var file = this.files[0];
            if (file.size > 5242880) {
                $(this).parent().parent().append('Недопустимый размер файла.');
            }
        });

        // кнопка "Сохранить"
        $(document).on('click', '.save-button', function() {
            $("#tagsForm").submit();
        });
    </script>

@stop