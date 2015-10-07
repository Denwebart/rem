<div class="box">
    <!-- Теги -->
    <div class="box-title">
        <h3>Теги</h3>
    </div>
    <div class="box-body">
        <div id="tags-area">
            <div class="tags">
                @foreach($page->tags as $tag)
                    <div class="btn-group tag" data-id="{{ $tag->id }}">
                        {{ Form::hidden("tags[$tag->id]", $tag->title) }}
                        <button type="button" class="btn btn-info btn-sm tag-title">{{ $tag->title }}</button>
                        <button type="button" class="btn btn-danger btn-sm remove-tag" title="Удалить тег">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            <div class="add-tag-input">
                <div class="form-group">
                    <div class="input-group">
                        {{ Form::text('tags[new]', null, ['class' => 'form-control', 'id' => 'tag-input', 'placeholder' => 'Добавить новый тег']) }}
                        <div class="input-group-btn">
                            <a href="javascript:void(0)" class="btn btn-success add-tag" title="Добавить тег" data-toggle="tooltip">
                                <i class="glyphicon glyphicon-ok"></i>
                            </a>
                        </div>
                    </div>
                    <small class="help-block" style="display: none"></small>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
    @parent

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
                source: "<?php echo URL::route('tagAutocomplete') ?>",
                minLength: 1,
                select: function(e, ui) {
                    addTag(ui.item.id, ui.item.value);
                    $(this).autocomplete('close');
                    $(this).val() = "";
                    return false;
                }
            });
            // добавление тега
            $('.add-tag').on('click', function() {
                var addedTagTitle = $('#tags-area').find('.add-tag-input input').val();
                if(addedTagTitle.trim() != '') {
                    addTag(0, addedTagTitle);
                    tagNumber++;
                } else {
                    $('.add-tag-input .help-block').show().text('Нельзя добавить пустой тег.');
                    $('.add-tag-input .form-group').addClass('has-error');
                }
            });

            function addTag(addedTagId, addedTagTitle) {
                var $tagBlock = $('#tags-area');

                var aTags = $tagBlock.find('.tag-title');
                var found;
                for (var i = 0; i < aTags.length; i++) {
                    if (aTags[i].textContent.toLowerCase() == addedTagTitle.toLowerCase()) {
                        found = aTags[i];
                        break;
                    }
                }
                if(found) {
                    $('.add-tag-input .help-block').show().text('Такой тег уже добавлен.');
                    $('.add-tag-input .form-group').addClass('has-error');
                } else {
                    var addedTagInputName = (0 != addedTagId)
                            ? 'tags['+ addedTagId +']'
                            : 'tags[newTags]['+ tagNumber +']';
                    var html = '<div class="btn-group tag" data-id="'+ addedTagId +'">' +
                            '<input name="'+ addedTagInputName +'" value="'+ addedTagTitle +'" type="hidden">' +
                            '<button class="btn btn-info btn-sm tag-title">'+ addedTagTitle +'</button>' +
                            '<button class="btn btn-danger btn-sm remove-tag">' +
                            '<i class="glyphicon glyphicon-remove"></i>' +
                            '</button></div>';

                    $tagBlock.find('.tags').append(html);
                    $('#tag-input').val('');
                    $tagBlock.find('.show-add-tag').toggleClass('btn-info btn-warning').html('<i class="glyphicon glyphicon-plus"></i>');
                }
            }

            // удаление тега
            $('.tags').on('click', '.remove-tag', function() {
                $(this).parent().remove();
            });
        });
    </script>
@stop