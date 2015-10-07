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

@section('script')
    @parent
    <script type="text/javascript">
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
    </script>
@stop