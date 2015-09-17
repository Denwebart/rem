<div class="col-md-12">
    <div class="box">
        <div class="box-title">
            <h3>Редактировать тег</h3>
        </div>
        <div class="box-body row">
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('image', 'Изображение') }}<br/>
                    {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                    {{ $errors->first('image') }}

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
                <div class="form-group">
                    {{ Form::label('title', 'Тег') }}
                    {{ Form::text('title', $tag->title, ['class' => 'form-control', 'placeholder' => 'Новый тег']) }}
                    {{ $errors->first('title') }}
                </div>
            </div>
            <div class="col-md-2">
                {{ Form::submit('Сохранить', ['class' => 'btn btn-success margin-top-25']) }}
                <a href="{{ URL::route('admin.tags.index') }}" class="btn btn-primary">Отмена</a>
            </div>
        </div>
    </div>
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
    </script>

@stop