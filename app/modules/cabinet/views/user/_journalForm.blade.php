<div class="col-md-7">
	<div class="form-group">
		{{ Form::label('parent_id', 'Категория', ['class' => 'control-label']) }}
		{{ Form::select('parent_id', Page::getJournalCategory(), $article->parent_id, ['class' => 'form-control']) }}
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

    <div class="form-group">
        {{ Form::label('tags', 'Теги') }}

        <div id="tags-area">
            @foreach($article->tags as $tag)
                <a href="javascript:void(0)" class="btn btn-info" data-tag-id="{{ $tag->id }}">
                    <span class="text">
                        {{ $tag->title }}
                    </span>
                </a>
            @endforeach
        </div>

        {{ Form::text('tags', null, ['class' => 'form-control autocomplete']) }}
        {{ $errors->first('tags') }}
    </div>

	{{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
	<a href="{{ URL::route('admin.pages.index') }}" class="btn btn-primary">Отмена</a>
</div>

@section('style')
    @parent
    <link rel="stylesheet" type="text/css" href="/css/selectize.css" />
    <link rel="stylesheet" type="text/css" href="/css/selectize.default.css" />

    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
@endsection

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
			{ name: 'smiley', items: ['Smiley']}
		]
	})
</script>

{{--<script type="text/javascript" src="/js/selectize.min.js"></script>--}}

{{--<script type="text/javascript">--}}

    {{--$('#tags').selectize({--}}
        {{--delimiter: ',',--}}
        {{--persist: false,--}}
        {{--create: function(input) {--}}
            {{--return {--}}
                {{--value: input,--}}
                {{--text: input--}}
            {{--}--}}
        {{--}--}}
    {{--});--}}
    {{----}}

{{--</script>--}}

<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">

    // автокомплит тегов
    $(".autocomplete").autocomplete({
        source: "<?php echo URL::route('user.journal.tagAutocomplete', ['login' => $user->getLoginForUrl()]) ?>",
        minLength: 1,
        select: function(e, ui) {
            var tag = '<a href="javascript:void(0)" class="btn btn-info" data-tag-id=""><span class="text">'+ ui.item.value +'</span></a>';
            $('#tags-area').append(tag);

//            $(this).val(ui.item.value);
//            $("#merge-tags-form").find('.error').empty();
        }
    });

</script>

@stop