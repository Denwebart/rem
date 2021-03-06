@extends('admin::layouts.admin')

<?php
$title = 'Объединение тегов';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-tags"></i>
            <i class="fa fa-plus"></i>
            <i class="fa fa-tags"></i>
            {{ $title }}
            <small>слияние похожих тегов</small>
        </h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li><a href="{{ URL::route('admin.tags.index') }}">Теги</a></li>
            <li class="active">{{ $title }}</li>
        </ol>
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-xs-12 margin-bottom-15">
                <a href="{{ URL::route('admin.tags.index') }}" class="btn btn-dashed">
                    <span>Все теги</span>
                </a>
                <a href="{{ URL::route('admin.tags.merge') }}" class="btn btn-primary">
                    <span>Объединенить теги</span>
                </a>
            </div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            {{ Form::open(['method' => 'POST', 'route' => ['admin.tags.postMerge'], 'id' => 'merge-tags-form']) }}
                                <div class="col-md-3">
                                    <div class="original-tags">
                                        <div class="form-group input first @if($errors->has('tags')) has-error @endif">
                                            {{ Form::text('tags[1]', null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'tags[1]', 'readonly' => 'readonly']) }}
                                            <small class="tags_error error help-block">
                                                {{ $errors->first('tags') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <span>объединить в</span>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group @if($errors->has('resultTag')) has-error @endif">
                                        {{ Form::text('resultTag', null, ['class' => 'form-control autocomplete', 'placeholder' => '', 'id' => 'resultTag']) }}
                                        <small class="resultTag_error error help-block">
                                            {{ $errors->first('resultTag') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <a href="javascript:void(0)" class="btn btn-primary pull-right margin-left-10" id="cancel-merge-tags-button">Отмена</a>
                                    {{ Form::submit('Объединить', ['class' => 'btn btn-success pull-left', 'id' => 'merge-tags-button']) }}
                                </div>
                                {{ Form::hidden('_token', csrf_token()) }}
                            {{ Form::close() }}
                        </div>

                        <hr/>

                        <div class="row">
                            <div class="col-md-6">
                                <h4>Поиск тега</h4>

                                {{ Form::open(['method' => 'GET', 'route' => ['admin.tags.search'], 'id' => 'search-tags-form', 'class' => 'form-inline']) }}

                                <div class="form-group @if($errors->has('query')) has-error @endif" style="width: 100%;">
                                    <div class="input-group" style="width: 100%;">
                                        {{ Form::text('query', null, ['class' => 'form-control', 'placeholder' => '']) }}
                                        <div class="input-group-btn">
                                            <button type="submit" id="search-tag" class="btn btn-success">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if($errors->has('query'))
                                        <small class="help-block">
                                            {{ $errors->first('query') }}
                                        </small>
                                    @endif
                                </div>

                                {{ Form::hidden('_token', csrf_token()) }}
                                {{ Form::close() }}
                            </div>
                        </div>

                        <div id="search-result" class="margin-top-10">

                        </div>

                    </div>
                </div>
            </div><!-- ./col -->
        </div>
    </div>
@stop

@section('style')
    @parent
    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
@stop

@section('script')
    @parent
    <script type="text/javascript">

        var inputNumber = 1;
        $('#merge-tags-form, #search-tags-form').trigger('reset');

        // автокомплит для тега, с которым сливаем
        $(".autocomplete").autocomplete({
            source: "<?php echo URL::route('admin.tags.autocomplete') ?>",
            minLength: 1,
            select: function(e, ui) {
                $(this).val(ui.item.value);
                $("#merge-tags-form").find('.error').empty();
            }
        });

        // кнопка отмены (очистка форм)
        $('#cancel-merge-tags-button').on('click', function() {
            $('#merge-tags-form, #search-tags-form').trigger('reset');
            $('.has-success').removeClass('has-success');
            $('.has-error').removeClass('has-error');
            $('.help-block').hide();
            var inputHtml = '<input value="" class="form-control" placeholder="" name="tags[1]" id="tags[1]" type="text">';
            $('.original-tags').html('');
            $('.original-tags').append('<div class="form-group input first">' + inputHtml + '</div>');
            $('#search-result').html('');
            inputNumber = 1;
        });

        // объединение тегов + валидация
        $('#merge-tags-form').submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $('#merge-tags-form'),
                data = $form.serialize(),
                url = $form.attr('action');
            $.ajax({
                url: url,
                dataType: "text json",
                type: "POST",
                data: {formData: data},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.fail) {
                        $.each(response.errors, function(index, value) {
                            var errorDiv = '.' + index + '_error';
                            $form.find(errorDiv).parent().addClass('has-error');
                            $form.find(errorDiv).empty().append(value);
                        });
                        $('#merge-tags-button').removeAttr('disabled');
                    }
                    if(response.success) {
                        $('#site-messages').prepend(response.message);
                        setTimeout(function() {
                            hideSiteMessage($('.site-message'));
                        }, 2000);
                        
                        $form.trigger('reset');
                        var inputHtml = '<input value="" class="form-control" placeholder="" name="tags[1]" id="tags[1]" type="text">';
                        $('.original-tags').html('');
                        $('.original-tags').append('<div class="input first">' + inputHtml + '</div>');
                        $('.has-success').removeClass('has-success');
                        $('#search-result').html('');
                        inputNumber = 1;
                    }
                }
            });
        });

        // поиск тегов
        $("#search-tags-form").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            $.ajax({
                url: url,
                dataType: "text json",
                type: "GET",
                data: {searchData: data},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success) {
                        $('#search-result').html(response.resultHtml);
                    }
                }
            });
        });

        // занесение выбранного тега в форму
        $("#search-result").on('click', '.add-to-input', function() {
            var $link = $(this);
            var tagId = $link.data('tagId');
            if(!$link.hasClass('selected')) {
                var linkText = $link.find('.tag-title').text().trim();
                var deleteInputHtml = '<a type="button" class="btn btn-danger btn-circle delete-input"><i class="glyphicon glyphicon-remove"></i></a>';
                if(inputNumber == 1) {
                    $("[id^='tags']").val(linkText);
                    $("[id^='tags']").parent().append(deleteInputHtml);
                    $("[id^='tags']").parent().attr('data-tag-id', tagId);
                    if($("[id^='tags']").parent().hasClass('has-error')) {
                        $("[id^='tags']").parent().toggleClass("has-error has-success");
                        $("[id^='tags']").parent().find('.help-block').hide();
                    } else {
                        $("[id^='tags']").parent().addClass("has-success");
                    }
                } else {
                    var plusHtml = '<button type="button" class="btn btn-default btn-circle btn-outline plus"><i class="glyphicon glyphicon-plus"></i></button>';
                    var inputHtml = '<input value="'+ linkText +'" class="form-control" placeholder="" name="tags['+inputNumber+']" id="tags['+inputNumber+']" type="text" readonly="readonly">';
                    $('.original-tags').append('<div class="form-group input has-success" data-tag-id="'+ tagId +'">' + plusHtml + inputHtml + deleteInputHtml + '</div>');
                }
                $('#merge-tags-button').removeAttr('disabled');
                $link.find('.tag-title').removeClass('btn-outline');
                $link.addClass('selected');
                inputNumber++;
            }
            else {
                $link.find('.tag-title').addClass('btn-outline');
                $link.removeClass('selected');
                var input = $('.original-tags').find('[data-tag-id='+ tagId +']');
                if(!input.hasClass('first')) {
                    input.remove();
                    inputNumber--;
                } else {
                    if(inputNumber <= 2) {
                        input.removeClass('has-success');
                        input.find('input').val('');
                        input.find('.delete-input').remove();
                        inputNumber = 1;
                    } else {
                        input.remove();
                        inputNumber--;
                        $(".original-tags").find('.input').first().addClass('first');
                        $(".original-tags").find('.input').first().find('.plus').remove();
                        $(".original-tags").find('.input').first().find('input').attr('id', 'tags[1]').attr('name', 'tags[1]');
                    }
                }
            }
        });

        // удаление выбранных тегов
        $(".original-tags").on('click', '.delete-input', function() {
            var $deleteLink = $(this);
            var tagId = $deleteLink.parent().data('tagId');
            $('#search-result').find('[data-tag-id='+ tagId +']').removeClass('selected');
            $('#search-result').find('[data-tag-id='+ tagId +']').find('.tag-title').addClass('btn-outline');
            $deleteLink.parent().attr('data-tag-id', '');
            if(!$deleteLink.parent().hasClass('first')) {
                $deleteLink.parent().remove();
                inputNumber--;
            } else {
                if(inputNumber <= 2) {
                    $deleteLink.parent().removeClass('has-success');
                    $deleteLink.parent().find('input').val('');
                    $deleteLink.remove();
                    inputNumber = 1;
                } else {
                    $deleteLink.parent().remove();
                    inputNumber--;
                    $(".original-tags").find('.input').first().addClass('first');
                    $(".original-tags").find('.input').first().find('.plus').remove();
                    $(".original-tags").find('.input').first().find('input').attr('id', 'tags[1]').attr('name', 'tags[1]');
                }
            }
        });

    </script>
@stop