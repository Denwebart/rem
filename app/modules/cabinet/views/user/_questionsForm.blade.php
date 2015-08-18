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
		{{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
		{{ $errors->first('image') }}

		@if($question->image)
			{{ $question->getImage(null, ['class' => 'page-image']) }}

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
	{{ Form::submit('Предпросмотр', ['name' => 'preview', 'class' => 'btn btn-warning']) }}
	{{ Form::submit('Сохранить', ['name' => 'save', 'class' => 'btn btn-success']) }}
</div>

@section('style')
    @parent

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init', ['imagePath' => $question->getImageEditorPath()])
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