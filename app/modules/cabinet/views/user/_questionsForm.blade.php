<div class="col-md-7">
	<div class="form-group">
		{{ Form::label('parent_id', 'Категория', ['class' => 'control-label']) }}
		{{ Form::select('parent_id', Page::getQuestionsCategory(), $question->parent_id, ['class' => 'form-control']) }}
		{{ $errors->first('parent_id') }}
	</div>
	<div class="form-group">
		{{ Form::label('title', 'Заголовок') }}
		{{ Form::text('title', $question->title, ['class' => 'form-control']) }}
		{{ $errors->first('title') }}
	</div>
</div>

<div class="col-md-5">
	<div class="form-group">
		{{ Form::label('image', 'Изображение') }}<br/>
		{{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs', 'id' => 'image']) }}
        {{ Form::hidden('image-url', $question->getImagePath() . $question->image, ['id' => 'image-name']) }}
		{{ $errors->first('image') }}

		@if($question->image)
            <div id="page-image">
			    {{ $question->getImage(null, ['class' => 'page-image']) }}
            </div>
			<a href="javascript:void(0)" id="delete-image">Удалить</a>
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
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
		{{ Form::label('content', 'Контент') }}
		{{ Form::textarea('content', $question->content, ['class' => 'form-control editor']) }}
		{{ $errors->first('content') }}
	</div>

    <!-- TinyMCE image -->
    {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}

	<a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}" class="btn btn-primary">Отмена</a>
	<a href="javascript:void(0)" class="btn btn-warning preview">Предпросмотр</a>
	{{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
</div>

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init', ['imagePath' => $question->getImageEditorPath()])

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

	<script type="text/javascript">
		$('.preview').on('click', function() {
			tinyMCE.get("content").save();
			var $form = $('form'),
				data = $form.serialize(),
				url = '<?php echo URL::route('user.preview', ['login' => $user->getLoginForUrl()])?>';
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
							$form.find(errorDiv).empty().append(value);
						});
					}
					if(data.success) {
						$('#form-area').hide();
						$('#preview').show().html(data.previewHtml);
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