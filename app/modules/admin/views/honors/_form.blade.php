<div class="col-md-5">
    <div class="box">
        <div class="box-title"></div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('title', 'Название', ['class' => 'control-label']) }}
                {{ Form::text('title', $honor->title, ['class' => 'form-control']) }}
                {{ $errors->first('title') }}
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('image', 'Изображение') }}<br/>
                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                        {{ $errors->first('image') }}
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
        </div>
    </div>
</div>

<div class="col-md-7">
    <div class="box">
        <div class="box-title"></div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::textarea('description', $honor->description, ['class' => 'form-control editor']) }}
                {{ $errors->first('description') }}
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <!-- TinyMCE image -->
    {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}

    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ URL::route('admin.honors.index') }}" class="btn btn-primary">Отмена</a>
</div>

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('admin::tinymce-init', ['page' => $honor])
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

@stop