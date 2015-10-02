<div class="row">
    <div class="col-md-12">
        <div class="button-group pull-right margin-bottom-20">
            <a href="{{ $backUrl }}" class="btn btn-primary btn-sm">
                <i class="material-icons">keyboard_arrow_left</i>
                <span class="hidden-xxs">Отмена</span>
            </a>
            <a href="javascript:void(0)" class="btn btn-warning btn-sm preview">
                <i class="material-icons visible-xxs">search</i>
                <span class="hidden-xxs">Предпросмотр</span>
            </a>

            {{ Form::hidden('backUrl', $backUrl) }}
            {{ Form::submit('Сохранить', ['class' => 'btn btn-success btn-sm']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            @if($question->image)
                <div id="page-image" class="margin-bottom-10">
                    {{ $question->getImage(null, ['class' => 'page-image']) }}
                    <a href="javascript:void(0)" id="delete-image" title="Удалить изображение" data-toggle="tooltip">
                        <i class="material-icons">delete</i>
                    </a>
                </div>
                @section('script')
                    @parent

                    <script type="text/javascript">
                        $('#delete-image').click(function(){
                            if(confirm('Вы уверены, что хотите удалить изображение?')) {
                                $.ajax({
                                    url: '<?php echo URL::route('user.deleteImageFromPage', ['login' => $user->getLoginForUrl(), 'id' => $question->id]) ?>',
                                    dataType: "text json",
                                    type: "POST",
                                    data: {field: 'image'},
                                    beforeSend: function(request) {
                                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                    },
                                    success: function(response) {
                                        if(response.success){
                                            $('#site-messages').prepend(response.message);
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

            {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary btn-full btn-sm file-inputs', 'id' => 'image']) }}
            {{ Form::hidden('image-url', ($question->image) ? $question->getImagePath() . $question->image : '', ['id' => 'image-name']) }}
            <small class="image_error error text-danger">
                {{ $errors->first('image') }}
            </small>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            {{ Form::label('parent_id', 'Категория', ['class' => 'control-label']) }}
            {{ Form::select('parent_id', Page::getQuestionsCategory(), $question->parent_id, ['class' => 'form-control']) }}
            <small class="parent_id_error error text-danger">
                {{ $errors->first('parent_id') }}
            </small>
        </div>
        <div class="form-group">
            {{ Form::hidden('type', $question->type) }}
            {{ Form::label('title', 'Заголовок') }}
            {{ Form::text('title', $question->title, ['class' => 'form-control']) }}
            <small class="title_error error text-danger">
                {{ $errors->first('title') }}
            </small>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('content', 'Текст вопроса') }}
            {{ Form::textarea('content', $question->content, ['class' => 'form-control editor']) }}
            <small class="content_error error text-danger">
                {{ $errors->first('content') }}
            </small>
        </div>

        <!-- TinyMCE image -->
        {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
        {{ Form::hidden('tempPath', $question->getTempPath(), ['id' => 'tempPath']) }}
    </div>
</div>

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init')

	<!-- FancyBox2 -->
	<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
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

	<!-- FancyBox2 -->
	{{HTML::script('fancybox/jquery.fancybox.pack.js?v=2.1.5')}}
	<script type="text/javascript">
		$(document).ready(function() {
			$(".fancybox").fancybox();
		});
	</script>

    {{ HTML::script('js/jRate.js') }}
	<script type="text/javascript">
        // убираем ошибку при изменении поля
        $('input, textarea').on('focus', function(){
            $(this).parent().find('.error').hide();
            $(this).parent().removeClass('has-error');
        });

		$('.preview').on('click', function() {
			tinyMCE.get("content").save();
			var $form = $('form'),
				data = $form.serialize(),
				url = '<?php echo URL::route('user.preview', ['login' => $user->getLoginForUrl(), 'id' => $question->id])?>';
			$.ajax({
				url: url,
				dataType: "text json",
				type: "POST",
				async: true,
				data: {formData: data},
				beforeSend: function(request) {
					return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
				},
				success: function(data) {
					if(data.fail) {
						$.each(data.errors, function(index, value) {
							var errorDiv = '.' + index + '_error';
							$form.find(errorDiv).parent().addClass('has-error');
							$form.find(errorDiv).empty().append(value).show();
						});
					}
					if(data.success) {
						$('#form-area').hide();
						$('#preview').show().html(data.previewHtml);
                        $("#jRate").jRate({
                            rating: '<?php echo $question->getRating(); ?>',
                            precision: 0, // целое число
                            width: 25,
                            height: 25,
                            startColor: '#03A9F4',
                            endColor: '#004B7D',
                            readOnly: 1
                        });
					} //success
				}
			});
		});

		$('#preview').on('click', '.preview-edit', function() {
			$('#form-area').show();
			$('#preview').hide();
		});

		$('#preview').on('click', '.preview-save', function() {
			$("#questionForm").submit();
		});
	</script>

    <script type="text/javascript">
        $('#image').change(function () {
            var fileData = new FormData();
            fileData.append('image', $('#image')[0].files[0]);
            $.ajax({
                type: 'POST',
                url: '<?php echo URL::route('postUploadImage', ['path' => urlencode($question->getImagePath())]) ?>',
                data: fileData,
                processData: false,
                contentType: false,
                dataType: "json",
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success) {
                        $('#page-image').html('<img src="'+ response.imageUrl +'" class="img-responsive page-image" title="" alt="">');
                        $('#image-url').val(response.imageUrl);
                    }
                }
            });
        });
    </script>

@stop