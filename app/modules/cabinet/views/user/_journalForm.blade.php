<div class="col-md-7">
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
        <div id="tags-area">
            <h4>Теги</h4>
            <div class="tags">
                @foreach($article->tags as $tag)
                    <div class="btn-group tag" data-id="{{ $tag->id }}">
                        {{ Form::hidden("tags[$tag->id]", $tag->title) }}
                        <a href="javascript:void(0)" class="btn btn-info btn-sm">{{ $tag->title }}</a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm remove-tag">
                            <i class="glyphicon glyphicon-remove"></i>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="row add-tag-input">
                <div class="col-xs-10">
                    <div class="form-group">
                        {{ Form::text('tags[new]', null, ['class' => 'form-control', 'id' => 'tag-input', 'placeholder' => 'Добавить новый тег']) }}
                        <small class="help-block" style="display: none"></small>
                    </div>
                </div>
                <div class="col-xs-2">
                    <a href="javascript:void(0)" class="btn btn-success btn-circle add-tag">
                        <i class="glyphicon glyphicon-ok"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

	{{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
	<a href="{{ URL::route('user.journal', ['login' => $user->getLoginForUrl()]) }}" class="btn btn-primary">Отмена</a>
</div>

@section('style')
    @parent
    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
    <script src="/js/jquery-ui.min.js"></script>
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

    <script type="text/javascript">
        $(document).ready(function() {
            // Теги
            var tagNumber = 0;
            // убираем ошибку при изменении поля
            $('#tag-input').on('focus', function(){
                $(this).parent().find('.help-block').hide().text('');
                $(this).parent().removeClass('has-error');
            });
            // автокомплит при добавлении тега
            $("#tag-input").autocomplete({
                source: "<?php echo URL::route('user.journal.tagAutocomplete', ['login' => $user->getLoginForUrl()]) ?>",
                minLength: 1,
                select: function(e, ui) {
//                    $(this).val(ui.item.value);
//                    $(this).attr('data-tag-id', ui.item.id);

                    addTag(ui.item.id, ui.item.value);
                    $(this).val('');
                }
            });
            // добавление тега
            $('.add-tag').on('click', function() {
                var addedTagTitle = $('#tags-area').find('.add-tag-input input').val();
                addTag(0, addedTagTitle);
                tagNumber++;
//                var $tagBlock = $('#tags-area'),
//                        addedTagTitle = $tagBlock.find('.add-tag-input input').val(),
//                        addedTagId = $tagBlock.find('.add-tag-input input').attr('data-tag-id');
//
//                var html = '<div class="btn-group tag" data-id="'+ addedTagId +'">' +
//                        '<input name="tags['+ addedTagId +']" value="'+ addedTagId +'" type="hidden">' +
//                        '<a href="javascript:void(0)" class="btn btn-info btn-sm">'+ addedTagTitle +'</a>' +
//                        '<a href="javascript:void(0)" class="btn btn-danger btn-sm remove-tag">' +
//                        '<i class="glyphicon glyphicon-remove"></i>' +
//                        '</a></div>';
//
//                $tagBlock.find('.tags').append(html);
//                $('#tag-input').val('');
//                $tagBlock.find('.show-add-tag').toggleClass('btn-info btn-warning').html('<i class="glyphicon glyphicon-plus"></i>');
            });

            function addTag(addedTagId, addedTagTitle) {
                var $tagBlock = $('#tags-area');
                var addedTagInputName = (0 != addedTagId)
                        ? 'tags['+ addedTagId +']'
                        : 'tags[newTags]['+ tagNumber +']';
                var html = '<div class="btn-group tag" data-id="'+ addedTagId +'">' +
                        '<input name="'+ addedTagInputName +'" value="'+ addedTagTitle +'" type="hidden">' +
                        '<a href="javascript:void(0)" class="btn btn-info btn-sm">'+ addedTagTitle +'</a>' +
                        '<a href="javascript:void(0)" class="btn btn-danger btn-sm remove-tag">' +
                        '<i class="glyphicon glyphicon-remove"></i>' +
                        '</a></div>';

                $tagBlock.find('.tags').append(html);
                $('#tag-input').val('');
                $tagBlock.find('.show-add-tag').toggleClass('btn-info btn-warning').html('<i class="glyphicon glyphicon-plus"></i>');
            }

            // удаление тега
            $('.tags').on('click', '.remove-tag', function() {
                $(this).parent().remove();
            });
        });
    </script>

@stop