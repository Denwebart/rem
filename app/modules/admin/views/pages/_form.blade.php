<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Основная информация</h3>
            <div class="pull-right author">
                {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-right']) }}
                <span class="pull-right">
                    {{ $page->user->login }}
                </span>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('parent_id', 'Родитель', ['class' => 'control-label']) }}
                {{ Form::select('parent_id', Page::getContainer(), $page->parent_id, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('alias', 'Алиас') }}
                {{ Form::text('alias', $page->alias, ['class' => 'form-control']) }}
                {{ $errors->first('alias') }}
            </div>
            <div class="form-group">
                {{ Form::label('title', 'Заголовок') }}
                {{ Form::text('title', $page->title, ['class' => 'form-control']) }}
                {{ $errors->first('title') }}
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('menu_title', 'Заголовок меню') }}
                        {{ Form::text('menu_title', $page->menu_title, ['class' => 'form-control']) }}
                        {{ $errors->first('menu_title') }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::label('is_container', 'Содержит подпункты') }}
                        {{ Form::hidden('is_container', 0) }}
                        {{ Form::checkbox('is_container', 1) }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::label('show_submenu', 'Показывать подменю') }}
                        {{ Form::hidden('show_submenu', 0) }}
                        {{ Form::checkbox('show_submenu', 1) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('image', 'Изображение') }}<br/>
                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                        {{ $errors->first('image') }}

                        @if($page->image)
                            {{ $page->getImage(null, ['class' => 'page-image']) }}

                            <a href="javascript:void(0)" id="delete-image">Удалить</a>
                            @section('script')
                                @parent

                                <script type="text/javascript">
                                    $('#delete-image').click(function(){
                                        if(confirm('Вы уверены, что хотите удалить изображение?')) {
                                            $.ajax({
                                                url: '<?php echo URL::route('admin.pages.deleteImage', ['id' => $page->id]) ?>',
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
                    <div class="col-sm-6">
                        {{ Form::label('image_alt', 'Альт к изображению') }}
                        {{ Form::textarea('image_alt', $page->image_alt, ['class' => 'form-control', 'rows' => 4]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box">
        <!-- Похожие -->
        <div class="box-title">
            <h3>Похожие</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="related related-articles">
                            <h4>Похожие статьи</h4>
                            <ul>
                                @foreach($page->relatedArticles as $articles)
                                    <li data-id="{{ $articles->id }}">
                                        {{ Form::hidden("relatedarticles[$articles->id]", $articles->id) }}
                                        <a href="{{ URL::to($articles->getUrl()) }}" target="_blank">
                                            {{ $articles->getTitle() }}
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-circle remove-related" title="Удалить">
                                            <i class="glyphicon glyphicon-remove"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row add-related-input">
                                <div class="col-xs-10">
                                    <div class="form-group">
                                        {{ Form::text('relatedarticles[new]', null, ['class' => 'form-control', 'id' => 'related-articles']) }}
                                        <small class="help-block" style="display: none"></small>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <a href="javascript:void(0)" class="cancel-related" title="Отмена">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-success btn-circle add-related" data-type="articles" data-type-id="{{ RelatedPage::TYPE_ARTICLE }}" title="Добавить похожую статью">
                                        <i class="glyphicon glyphicon-ok"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="related related-questions">
                            <h4>Похожие вопросы</h4>
                            <ul>
                                @foreach($page->relatedQuestions as $question)
                                    <li data-id="{{ $question->id }}">
                                        {{ Form::hidden("relatedquestions[$question->id]", $question->id) }}
                                        <a href="{{ URL::to($question->getUrl()) }}" target="_blank">
                                            {{ $question->getTitle() }}
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-circle remove-related" title="Удалить">
                                            <i class="glyphicon glyphicon-remove"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row add-related-input">
                                <div class="col-xs-10">
                                    <div class="form-group">
                                        {{ Form::text('relatedquestions[new]', null, ['class' => 'form-control', 'id' => 'related-questions']) }}
                                        <small class="help-block" style="display: none"></small>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <a href="javascript:void(0)" class="cancel-related" title="Отмена">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-success btn-circle add-related" data-type="questions" data-type-id="{{ RelatedPage::TYPE_QUESTION }}" title="Добавить похожий вопрос">
                                        <i class="glyphicon glyphicon-ok"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Page::TYPE_ARTICLE == $page->type)
        <div class="box">
            <!-- Теги -->
            <div class="box-title">
                <h3>Теги</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="tags-area">
                                <div class="tags">
                                    @foreach($page->tags as $tag)
                                        <div class="btn-group tag" data-id="{{ $tag->id }}">
                                            {{ Form::hidden("tags[$tag->id]", $tag->title) }}
                                            <button type="button" class="btn btn-info tag-title">{{ $tag->title }}</button>
                                            <button type="button" class="btn btn-danger remove-tag" title="Удалить тег">
                                                <i class="glyphicon glyphicon-remove"></i>
                                            </button>
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
                                        <a href="javascript:void(0)" class="btn btn-success btn-circle add-tag" title="Добавить тег">
                                            <i class="glyphicon glyphicon-ok"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="col-md-5">
    <div class="box">
        <div class="box-title">
            <h3>Мета-теги SEO</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('meta_title', 'Мета-тег Title') }}
                {{ Form::textarea('meta_title', $page->meta_title, ['class' => 'form-control', 'rows' => 2]) }}
                {{ $errors->first('meta_title') }}
            </div>
            <div class="form-group">
                {{ Form::label('meta_desc', 'Мета-тег Description') }}
                {{ Form::textarea('meta_desc', $page->meta_desc, ['class' => 'form-control', 'rows' => 3]) }}
                {{ $errors->first('meta_desc') }}
            </div>
            <div class="form-group">
                {{ Form::label('meta_key', 'Мета-тег Keywords') }}
                {{ Form::textarea('meta_key', $page->meta_key, ['class' => 'form-control', 'rows' => 3]) }}
                {{ $errors->first('meta_key') }}
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-title">
            <h3>Дата публикации</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('is_published', 'Опубликован') }}
                        {{ Form::hidden('is_published', 0, ['id' => 'is_published_uncheck']) }}
                        {{ Form::checkbox('is_published', 1) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('published_at', 'Дата публикации') }}

                        <div class="input-group">
                            {{ Form::text('published_at',
                                !is_null($page->published_at) ? date('d-m-Y', strtotime($page->published_at)) : '',
                                ['class' => 'form-control datepicker-input'])
                            }}
                            <span id="published_at_time" class="input-group-addon">
                                {{ Form::hidden('publishedTime', !is_null($page->published_at) ? date('H:i:s', strtotime($page->published_at)) : Config::get('settings.defaultPublishedTime'), ['id' => 'publishedTime'])}}
                                {{ !is_null($page->published_at) ? date('H:i:s', strtotime($page->published_at)) : '' }}
                            </span>
                        </div>

                        {{ $errors->first('published_at') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="box">
        <div class="box-title">
            <h3>Контент страницы</h3>
        </div>
        <div class="box-body">
            <div class="box">
                <div class="box-title">
                    {{ Form::label('introtext', 'Краткое описание') }}
                    <div class="pull-right box-toolbar">
                        <a href="#" class="btn btn-link btn-xs collapse-box"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="box-body no-padding" style="display: none">
                    <div class="form-group">
                        {{ Form::textarea('introtext', $page->introtext, ['class' => 'form-control editor']) }}
                        {{ $errors->first('introtext') }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                {{ Form::label('content', 'Контент') }}
                {{ Form::textarea('content', $page->content, ['class' => 'form-control editor']) }}
                {{ $errors->first('content') }}
            </div>

            {{ Form::hidden('backUrl', $backUrl) }}

            {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
            <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
        </div>
    </div>
</div>

@section('style')
    @parent
    <link rel="stylesheet" href="/backend/css/datepicker/datepicker.css" />

    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
    <script src="/js/jquery-ui.min.js"></script>
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

    <!-- Date picker -->
    <script src="/backend/js/plugins/datepicker/datepicker.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.datepicker-input').datepicker({
            format: "dd-mm-yyyy"
        }).on('changeDate', function(ev){
            $("#published_at_time").text("<?php echo Config::get('settings.defaultPublishedTime')?>");
        });
    </script>

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replaceAll('editor')
    </script>

    <!-- iCheck -->
    <script src="/backend/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type='checkbox'], input[type='radio']").iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#is_published').on('ifUnchecked', function(event){
                $('#published_at').val('');
                $('#published_at_time').text('');
            });
            $('#published_at').on('change', function(){
                if(this.value == ''){
                    $('#published_at_time').text('');
                }
            });
            $('#published_at').datepicker().on('changeDate', function(ev){
                $('#is_published').iCheck('check');
            });

            // Related
            // кнопка отмена: очистка поля
            $('.cancel-related').on('click', function() {
                $(this).parent().parent().find('input').val('');
                $(this).parent().parent().find('.help-block').hide().text('');
                $(this).parent().parent().find('.form-group').removeClass('has-error');
            });
            // убираем ошибку при изменении поля
            $('#related-articles, #related-questions').on('focus', function(){
                $(this).parent().find('.help-block').hide().text('');
                $(this).parent().removeClass('has-error');
            });
            // автокомплит при добавлении похожей страницы
            $("#related-articles").autocomplete({
                source: "<?php echo URL::route('admin.pages.articlesAutocomplete') ?>",
                minLength: 3,
                select: function(e, ui) {
                    $(this).val(ui.item.value);
                    $(this).attr('data-page-id', ui.item.id);
                }
            });
            $("#related-questions").autocomplete({
                source: "<?php echo URL::route('admin.pages.questionsAutocomplete') ?>",
                minLength: 3,
                select: function(e, ui) {
                    $(this).val(ui.item.value);
                    $(this).attr('data-page-id', ui.item.id);
                }
            });
            // добавление похожей страницы
            $('.add-related').on('click', function() {
                var type = $(this).data('type'),
                    $relatedBlock = $('.related-' + type),
                    addedPageTitle = $relatedBlock.find('.add-related-input input').val(),
                    addedPageId = $relatedBlock.find('.add-related-input input').attr('data-page-id');

                if(addedPageTitle.trim() != '') {
                    $.ajax({
                        url: '/admin/pages/checkRelated' ,
                        dataType: "text json",
                        type: "POST",
                        data: {
                            addedPageTitle: addedPageTitle,
                            addedPageId: addedPageId,
                            typeId: $(this).data('typeId')
                        },
                        beforeSend: function(request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        },
                        success: function(response) {
                            if(response.success){
                                var html = '<li data-id="'+ addedPageId +'" class="success"><input name="related'+ type +'['+ addedPageId +']" value="'+ addedPageId +'" type="hidden">' +
                                        '<a href="'+ response.pageUrl +'" target="_blank">' +
                                        $relatedBlock.find('.add-related-input input').val() +
                                        '</a>' +
                                        '<a href="javascript:void(0)" class="btn btn-danger btn-circle remove-related">' +
                                        '<i class="glyphicon glyphicon-remove"></i>'+
                                        '</a></li>';
                                $relatedBlock.find('ul').append(html);
                                $relatedBlock.find('.add-related-input').slideUp();
                                $relatedBlock.find('.show-add-related').toggleClass('btn-info btn-warning').html('<i class="glyphicon glyphicon-plus"></i>');
                                $('#related-' + type).attr('data-page-id', '');
                            } else {
                                $relatedBlock.find('.add-related-input .help-block').show().text(response.message);
                                $relatedBlock.find('.add-related-input .form-group').addClass('has-error');
                            }
                        }
                    });
                } else {
                    $relatedBlock.find('.add-related-input .help-block').show().text('Введите заголовок страницы.');
                    $relatedBlock.find('.add-related-input .form-group').addClass('has-error');
                }
            });
            // удаление похожей страницы
            $('.related').on('click', '.remove-related', function() {
                $(this).parent().remove();
            });
        });
    </script>

    @if(Page::TYPE_ARTICLE == $page->type)
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
                                '<a href="javascript:void(0)" class="btn btn-info btn-sm tag-title">'+ addedTagTitle +'</a>' +
                                '<a href="javascript:void(0)" class="btn btn-danger btn-sm remove-tag">' +
                                '<i class="glyphicon glyphicon-remove"></i>' +
                                '</a></div>';

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
    @endif
@stop