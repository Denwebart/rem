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
            <div id="page-image" class="margin-bottom-10">
                @if($article->image)
                    {{ $article->getImage(null, ['class' => 'page-image']) }}
                    <a href="javascript:void(0)" id="delete-image">
                        <i class="material-icons">delete</i>
                    </a>
                @endif
            </div>
            @if($article->image)
                @section('script')
                    @parent

                    <script type="text/javascript">
                        $('#delete-image').click(function(){
                            if(confirm('Вы уверены, что хотите удалить изображение?')) {
                                $.ajax({
                                    url: '<?php echo URL::route('user.deleteImageFromPage', ['login' => $user->getLoginForUrl(), 'id' => $article->id]) ?>',
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

            {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary btn-sm btn-full file-inputs ajax-upload']) }}
            {{ Form::hidden('image_url', ($article->image) ? $article->getImagePath() . $article->image : '', ['id' => 'image_url']) }}
            <small class="image_error error text-danger">
                {{ $errors->first('image') }}
            </small>
            <small class="info">
                {{ Config::get('settings.maxImageSizeInfo') }}
            </small>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group @if($errors->first('title')) has-error @endif">
            {{ Form::hidden('type', $article->type) }}
            {{ Form::label('title', 'Заголовок', ['class' => 'control-label']) }}
            {{ Form::text('title', $article->title, ['class' => 'form-control']) }}
            <small class="title_error error text-danger">
                {{ $errors->first('title') }}
            </small>
            <small class="alias_error error text-danger">
                {{ $errors->first('alias') }}
            </small>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group @if($errors->first('content')) has-error @endif">
            {{ Form::label('content', 'Текст статьи', ['class' => 'control-label']) }}
            {{ Form::textarea('content', $article->content, ['class' => 'form-control editor']) }}
            <small class="content_error error text-danger">
                {{ $errors->first('content') }}
            </small>
        </div>

        <div class="form-group">
            <div id="tags-area">
                <h4>Теги</h4>
                <div class="tags">
                    @foreach($article->tags as $tag)
                        <div class="btn-group tag" data-id="{{ $tag->id }}">
                            {{ Form::hidden("tags[$tag->id]", $tag->title) }}
                            <a href="javascript:void(0)" class="btn btn-info btn-sm tag-title">{{ $tag->title }}</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm remove-tag">
                                <i class="material-icons">close</i>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="row add-tag-input">
                    <div class="col-sm-10 col-xs-9">
                        <div class="form-group">
                            {{ Form::text('tags[new]', null, ['class' => 'form-control', 'id' => 'tag-input', 'placeholder' => 'Добавить новый тег']) }}
                            <small class="error text-danger" style="display: none"></small>
                        </div>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <a href="javascript:void(0)" class="btn btn-success btn-sm btn-circle add-tag" title="Добавить тег" data-toggle="tooltip">
                            <i class="material-icons">done</i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- TinyMCE image -->
        {{ Form::file('editor_image', ['style' => 'display:none', 'id' => 'editor_image']) }}
        {{ Form::hidden('tempPath', $article->getTempPath(), ['id' => 'tempPath']) }}
    </div>
</div>

@section('style')
    @parent
    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
    <script src="/js/jquery-ui.min.js"></script>

    <!-- TinyMCE -->
    {{ HTML::script('js/tinymce/tinymce.min.js') }}
    @include('tinymce-init')
@stop

@section('script')
    @parent

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();
    </script>

    <!-- FancyBox2 -->
    {{-- стили в fonts.css --}}
    {{--<link rel="stylesheet" href="/fancybox/jquery.fancybox.min.css?v=2.1.5" type="text/css" media="screen" />--}}
    {{HTML::script('fancybox/jquery.fancybox.pack.min.js?v=2.1.5')}}
    <script type="text/javascript">
        $(document).ready(function() {
            $(".fancybox").fancybox();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            // убираем ошибку при изменении поля
            $('#tag-input, input, textarea').on('focus', function(){
                $(this).parent().find('.error').hide().text('');
                $(this).parent().removeClass('has-error');
            });
            // Теги
            var tagNumber = 0;
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
                    $('.add-tag-input .error').show().text('Нельзя добавить пустой тег.');
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
                    $('.add-tag-input .error').show().text('Такой тег уже добавлен.');
                    $('.add-tag-input .form-group').addClass('has-error');
                } else {
                    var addedTagInputName = (0 != addedTagId)
                            ? 'tags['+ addedTagId +']'
                            : 'tags[newTags]['+ tagNumber +']';
                    var html = '<div class="btn-group tag" data-id="'+ addedTagId +'">' +
                            '<input name="'+ addedTagInputName +'" value="'+ addedTagTitle +'" type="hidden">' +
                            '<a href="javascript:void(0)" class="btn btn-info btn-sm tag-title">'+ addedTagTitle +'</a>' +
                            '<a href="javascript:void(0)" class="btn btn-danger btn-sm remove-tag">' +
                            '<i class="material-icons">close</i>' +
                            '</a></div>';

                    $tagBlock.find('.tags').append(html);
                    $('#tag-input').val('');
                    $tagBlock.find('.show-add-tag').toggleClass('btn-info btn-warning').html('<i class="material-icons">close</i>');
                }
            }

            // удаление тега
            $('.tags').on('click', '.remove-tag', function() {
                $(this).parent().remove();
            });
        });
    </script>

    {{ HTML::script('js/jRate.min.js') }}
    <script type="text/javascript">
        $('.preview').on('click', function() {
            tinyMCE.get("content").save();
            var $form = $('form'),
                    data = $form.serialize(),
                    url = '<?php echo URL::route('user.preview', ['login' => $user->getLoginForUrl(), 'id' => $article->id])?>';
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
                        $('.mce-floatpanel').hide();
                        $('#preview').show().html(data.previewHtml);
                        $("#jRate").jRate({
                            rating: '<?php echo $article->getRating(); ?>',
                            precision: 0, // целое число
                            width: 25,
                            height: 25,
                            shapeGap: '5px',
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
            $("#articleForm").submit();
        });
    </script>

    <!-- Загрузка изображения ajax -->
    <script type="text/javascript">
        $('.ajax-upload').on('change', function () {
            if (this.files[0].size > 5242880) {
                $('form').find('.image_error').parent().addClass('has-error');
                $('form').find('.image_error').empty().append('Недопустимый размер файла.').show();
            } else {
                var fileData = new FormData();
                fileData.append('image', $(this)[0].files[0]);
                fileData.append('tempPath', $('#tempPath').val());
                $.ajax({
                    type: 'POST',
                    url: '<?php echo URL::route('uploadIntoTemp') ?>',
                    data: fileData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.fail) {
                            $.each(response.errors, function(index, value) {
                                var errorDiv = '.' + index + '_error';
                                $('form').find(errorDiv).parent().addClass('has-error');
                                $('form').find(errorDiv).empty().append(value).show();
                            });
                        }
                        if(response.success) {
                            $('#page-image').html(response.imageHtml);
                            $('#image_url').val(response.imageUrl);

                            $('form').find('.image_error').parent().removeClass('has-error');
                            $('form').find('.image_error').empty().hide();
                        }
                    }
                });
            }


        });

        <!-- Удаление временного изображения ajax -->
        $('#page-image').on('click', '#delete-temp-image', function(){
            var $button = $(this);
            if(confirm('Вы уверены, что хотите удалить изображение?')) {
                var imageName = $(this).parent().parent().find('.file-input-name');
                $.ajax({
                    url: '<?php echo URL::route('deleteFromTemp') ?>',
                    dataType: "text json",
                    type: "POST",
                    data: {'imageName': imageName.text(), 'tempPath': $('#tempPath').val()},
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $('#site-messages').prepend(response.message);
                            $button.css('display', 'none');
                            $('.page-image').remove();
                            imageName.text('');
                            $('#image_url').val('');
                            $('.ajax-upload').val('');

                            $('form').find('.image_error').parent().removeClass('has-error');
                            $('form').find('.image_error').empty().hide();
                        }
                    }
                });
            }
        });
    </script>

@stop