<?php
$disabled = ($page->type != Page::TYPE_SYSTEM_PAGE && $page->type != Page::TYPE_JOURNAL && $page->type != Page::TYPE_QUESTIONS)
        ? []
        : ['disabled' => 'disabled'];
?>

<div class="col-md-7">
    <div class="box">
        <div class="box-title">
            <h3>Основная информация</h3>
            <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}" target="_blank" class="pull-right author">
                {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-right']) }}
                    <span class="pull-right">
                    {{ $page->user->login }}
                </span>
            </a>
        </div>
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('parent_id', 'Родитель', ['class' => 'control-label']) }}
                {{ Form::select('parent_id', Page::getContainer(), $page->parent_id, ['class' => 'form-control'] + $disabled) }}
            </div>
            <div class="form-group @if($errors->has('alias')) has-error @endif">
                {{ Form::label('alias', 'Алиас') }}
                {{ Form::text('alias', $page->alias, ['class' => 'form-control'] + $disabled) }}
                @if($errors->has('alias'))
                    <small class="help-block">
                        {{ $errors->first('alias') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('title')) has-error @endif">
                {{ Form::label('title', 'Заголовок') }}
                {{ Form::text('title', $page->title, ['class' => 'form-control']) }}
                @if($errors->has('title'))
                    <small class="help-block">
                        {{ $errors->first('title') }}
                    </small>
                @endif
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group @if($errors->has('menu_title')) has-error @endif">
                        {{ Form::label('menu_title', 'Заголовок меню') }}
                        {{ Form::text('menu_title', $page->menu_title, ['class' => 'form-control']) }}
                        @if($errors->has('menu_title'))
                            <small class="help-block">
                                {{ $errors->first('menu_title') }}
                            </small>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {{ Form::label('is_container', 'Категория') }}
                        {{ Form::hidden('is_container', 0) }}
                        {{ Form::checkbox('is_container', 1) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('show_submenu', 'Показывать подменю') }}
                        {{ Form::hidden('show_submenu', 0) }}
                        {{ Form::checkbox('show_submenu', 1) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-6">
                    <div class="form-group display-inline-block @if($errors->has('image')) has-error @endif">
                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs pull-left']) }}

                        @if($page->image)
                            <a href="javascript:void(0)" id="delete-image" title="Удалить изображение" data-toggle="tooltip">
                                <i class="fa fa-trash"></i>
                            </a>
                            @section('script')
                                @parent

                                <script type="text/javascript">
                                    $('#delete-image').click(function(){
                                        var $deleteButton = $(this);
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
                                                        $deleteButton.css('display', 'none');
                                                        $deleteButton.nextAll('.tooltip:first').remove();
                                                        $('.page-image').remove();
                                                    }
                                                }
                                            });
                                        }
                                    });
                                </script>
                            @stop
                        @endif

                        @if($errors->has('image'))
                            <small class="help-block">
                                {{ $errors->first('image') }}
                            </small>
                        @endif

                        @if($page->image)
                            <div class="clearfix"></div>
                            {{ $page->getImage(null, ['class' => 'page-image margin-top-10']) }}
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-6">
                    <div class="form-group @if($errors->has('image_alt')) has-error @endif">
                        {{ Form::label('image_alt', 'Альт к изображению') }}
                        {{ Form::textarea('image_alt', $page->image_alt, ['class' => 'form-control', 'rows' => 4]) }}
                        @if($errors->has('image_alt'))
                            <small class="help-block">
                                {{ $errors->first('image_alt') }}
                            </small>
                        @endif
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
            <div class="row">
                <div class="col-sm-6">
                    <div class="related related-articles">
                        <h4>Похожие статьи</h4>
                        <ul>
                            @foreach($page->relatedArticles as $articles)
                                <li data-id="{{ $articles->id }}">
                                    <a href="javascript:void(0)" class="remove-related" title="Удалить" data-toggle="tooltip">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </a>
                                    {{ Form::hidden("relatedarticles[$articles->id]", $articles->id) }}
                                    <a href="{{ URL::to($articles->getUrl()) }}" target="_blank">
                                        {{ $articles->getTitle() }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="add-related-input">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ Form::text('relatedarticles[new]', null, ['class' => 'form-control', 'id' => 'related-articles']) }}
                                    <div class="input-group-btn">
                                        <a href="javascript:void(0)" class="btn btn-success add-related" data-type="articles" data-type-id="{{ RelatedPage::TYPE_ARTICLE }}" title="Добавить похожую статью" data-toggle="tooltip">
                                            <i class="glyphicon glyphicon-ok"></i>
                                        </a>
                                    </div>
                                </div>
                                <small class="help-block" style="display: none"></small>
                            </div>

                            <!-- Очистить поле -->
                            <a href="javascript:void(0)" class="cancel-related" title="Очистить" data-toggle="tooltip" style="display: none">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="related related-questions">
                        <h4>Похожие вопросы</h4>
                        <ul>
                            @foreach($page->relatedQuestions as $question)
                                <li data-id="{{ $question->id }}">
                                    <a href="javascript:void(0)" class="remove-related" title="Удалить" data-toggle="tooltip">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </a>
                                    {{ Form::hidden("relatedquestions[$question->id]", $question->id) }}
                                    <a href="{{ URL::to($question->getUrl()) }}" target="_blank">
                                        {{ $question->getTitle() }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="add-related-input">
                            <div class="form-group">
                                <div class="input-group">
                                    {{ Form::text('relatedquestions[new]', null, ['class' => 'form-control', 'id' => 'related-questions']) }}
                                    <div class="input-group-btn">
                                        <a href="javascript:void(0)" class="btn btn-success add-related" data-type="questions" data-type-id="{{ RelatedPage::TYPE_QUESTION }}" title="Добавить похожий вопрос" data-toggle="tooltip">
                                            <i class="glyphicon glyphicon-ok"></i>
                                        </a>
                                    </div>
                                </div>
                                <small class="help-block" style="display: none"></small>
                            </div>

                            <!-- Очистить поле -->
                            <a href="javascript:void(0)" class="cancel-related" title="Очистить" data-toggle="tooltip" style="display: none">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
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
    @endif
</div>

<div class="col-md-5">
    <div class="box">
        <div class="box-title">
            <h3>Мета-теги SEO</h3>
        </div>
        <div class="box-body">
            <div class="form-group @if($errors->has('meta_title')) has-error @endif">
                {{ Form::label('meta_title', 'Мета-тег Title') }}
                {{ Form::textarea('meta_title', $page->meta_title, ['class' => 'form-control', 'rows' => 4]) }}
                @if($errors->has('meta_title'))
                    <small class="help-block">
                        {{ $errors->first('meta_title') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('meta_desc')) has-error @endif">
                {{ Form::label('meta_desc', 'Мета-тег Description') }}
                {{ Form::textarea('meta_desc', $page->meta_desc, ['class' => 'form-control', 'rows' => 5]) }}
                @if($errors->has('meta_desc'))
                    <small class="help-block">
                        {{ $errors->first('meta_desc') }}
                    </small>
                @endif
            </div>
            <div class="form-group @if($errors->has('meta_key')) has-error @endif">
                {{ Form::label('meta_key', 'Мета-тег Keywords') }}
                {{ Form::textarea('meta_key', $page->meta_key, ['class' => 'form-control', 'rows' => 5]) }}
                @if($errors->has('meta_key'))
                    <small class="help-block">
                        {{ $errors->first('meta_key') }}
                    </small>
                @endif
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-title">
            <h3>Дата публикации</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-5 col-md-12 col-sm-6">
                    <div class="form-group margin-top-25 md-margin-top-0 xs-margin-top-0">
                        {{ Form::label('is_published', 'Опубликован') }}
                        {{ Form::hidden('is_published', 0, ['id' => 'is_published_uncheck']) }}
                        {{ Form::checkbox('is_published', 1) }}
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 col-sm-6">
                    <div class="form-group @if($errors->has('published_at')) has-error @endif">
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
                        @if($errors->has('published_at'))
                            <small class="help-block">
                                {{ $errors->first('published_at') }}
                            </small>
                        @endif
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
                <div class="box-title collapse-box-panel">
                    {{ Form::label('introtext', 'Краткое описание') }}
                    <div class="pull-right box-toolbar">
                        @if(!$page->introtext)
                            <i class="fa fa-chevron-down"></i>
                        @else
                            <i class="fa fa-chevron-up"></i>
                        @endif
                    </div>
                </div>
                <div class="box-body no-padding" @if(!$page->introtext) style="display: none" @endif>
                    <div class="form-group @if($errors->has('introtext')) has-error @endif">
                        {{ Form::textarea('introtext', $page->introtext, ['class' => 'form-control editor']) }}
                        @if($errors->has('introtext'))
                            <small class="help-block">
                                {{ $errors->first('introtext') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group @if($errors->has('content')) has-error @endif">
                {{ Form::label('content', 'Текст страницы') }}
                {{ Form::textarea('content', $page->content, ['class' => 'form-control editor']) }}

                @if($errors->has('content'))
                    <small class="help-block">
                        {{ $errors->first('content') }}
                    </small>
                @endif
            </div>

            <!-- TinyMCE image -->
            {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
            {{ Form::hidden('tempPath', $page->getTempPath(), ['id' => 'tempPath']) }}

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

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('admin::tinymce-init', ['imagePath' => $page->getImageEditorPath()])
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

            $(".collapse-box-panel").click(function () {
                var n = $(this).next(".box-body");
                if (n.is(":visible")) {
                    $(this).find("i").removeClass("fa-chevron-up");
                    $(this).find("i").addClass("fa-chevron-down")
                } else {
                    $(this).find("i").removeClass("fa-chevron-down");
                    $(this).find("i").addClass("fa-chevron-up")
                }
                n.slideToggle("slow")
            });

            // Related
            // кнопка отмена: очистка поля
            $('.cancel-related').on('click', function() {
                $(this).parent().parent().find('input').val('');
                $(this).parent().parent().find('.input-group').removeClass('has-error');
                $(this).parent().parent().find('.help-block').hide().text('');
                $(this).parent().parent().find('.form-group').removeClass('has-error');
                $(this).nextAll('.tooltip:first').remove();
                $(this).hide();
            });
            $('#related-articles, #related-questions').on('keyup', function(){
                $(this).parent().parent().parent().find('.cancel-related').show();
            });
            // убираем ошибку при изменении поля
            $('#related-articles, #related-questions').on('focus', function(){
                $(this).parent().parent().find('.input-group').removeClass('has-error');
                $(this).parent().parent().find('.help-block').hide().text('');
                $(this).parent().parent().removeClass('has-error');
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
                                        '<a href="javascript:void(0)" class="remove-related" title="Удалить" data-toggle="tooltip">' +
                                        '<i class="glyphicon glyphicon-remove"></i>'+
                                        '</a>' +
                                        '<a href="'+ response.pageUrl +'" target="_blank">' +
                                        $relatedBlock.find('.add-related-input input').val() +
                                        '</a></li>';
                                $relatedBlock.find('ul').append(html);
                                $relatedBlock.find('.add-related-input input').val('');
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
    @endif
@stop