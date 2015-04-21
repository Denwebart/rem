<div class="col-md-7">
	<div class="form-group">
		{{ Form::label('parent_id', 'Категория', ['class' => 'control-label']) }}
		{{ Form::select('parent_id', Page::getContainer(), $article->parent_id, ['class' => 'form-control']) }}
		{{ $errors->first('parent_id') }}
	</div>
	<div class="form-group">
		{{ Form::label('title', 'Заголовок') }}
		{{ Form::text('title', $article->title, ['class' => 'form-control']) }}
		{{ $errors->first('title') }}
	</div>
</div>

<div class="col-md-5">
	<div class="form-group">
		{{ Form::label('image', 'Изображение') }}<br/>
		{{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
		{{ $errors->first('image') }}
		@if($article->image_alt)
			<img src="" alt=""/>
			{{ HTML::image($article->image_alt) }}
		@endif
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
		{{ Form::label('content', 'Контент') }}
		{{ Form::textarea('content', $article->content, ['class' => 'form-control editor']) }}
		{{ $errors->first('content') }}
	</div>

	{{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
	<a href="{{ URL::route('admin.pages.index') }}" class="btn btn-primary">Отмена</a>
</div>

@section('script')
@parent

<script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
<script type="text/javascript">
	CKEDITOR.replace('content', {
		toolbar: [
			[ 'Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo' ],
			{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
			{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
			{ name: 'links', items: [ 'Link', 'Unlink'] },
		]
	})
</script>

@stop