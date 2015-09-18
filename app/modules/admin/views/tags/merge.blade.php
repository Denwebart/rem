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
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li><a href="{{ URL::route('admin.tags.index') }}">Теги</a></li>
            <li class="active">{{ $title }}</li>
        </ol>
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">

                        <div class="message"></div>

                        <div class="row">
                            {{ Form::open(['method' => 'POST', 'route' => ['admin.tags.postMerge'], 'id' => 'merge-tags-form']) }}
                                <div class="col-md-4">
                                    <div class="original-tags">
                                        {{--{{ Form::label('tags[1]', 'Тег', ['class' => 'col-sm-2 control-label']) }}--}}
                                        <div class="form-group input first">
                                            {{ Form::text('tags[1]', null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'tags[1]']) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <span>объединить в</span>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::text('resultTag', null, ['class' => 'form-control autocomplete', 'placeholder' => '', 'id' => 'resultTag']) }}
                                        {{ $errors->first('resultTag') }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    {{ Form::submit('Объединить', ['class' => 'btn btn-success margin-top-25', 'id' => 'merge-tags-button']) }}
                                    <a href="javascript:void(0)" class="btn btn-primary" id="cancel-merge-tags-button">Отмена</a>
                                </div>
                                {{ Form::hidden('_token', csrf_token()) }}
                            {{ Form::close() }}
                        </div>

                        <hr/>

                        <div class="row">
                            {{ Form::open(['method' => 'POST', 'route' => ['admin.tags.search'], 'id' => 'search-tags-form', 'class' => 'form-inline']) }}

                            <div class="form-group">
                                {{ Form::label('search', 'Поиск тега', ['class' => 'col-sm-2 control-label']) }}
                                {{ Form::text('search', null, ['class' => 'form-control', 'placeholder' => '']) }}
                                {{ $errors->first('search') }}
                            </div>

                                {{ Form::submit('Найти', ['class' => 'btn btn-success margin-top-25']) }}
                                {{ Form::hidden('_token', csrf_token()) }}
                            {{ Form::close() }}

                        </div>

                        <div id="search-result">

                        </div>

                    </div>
                </div>
            </div><!-- ./col -->
        </div>
    </div>
@stop

@section('style')
    @parent
    <link rel="stylesheet" href="/backend/css/bootstrapValidator/bootstrapValidator.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
    {{--<script src="/js/jquery-ui.min.js"></script>--}}
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
        $('#merge-tags-form').bootstrapValidator({
            fields: {
                'tags[1]': {
                    validators: {
                        notEmpty: {
                            message: 'Поле не может быть пустым'
                        }
                    }
                },
                resultTag: {
                    validators: {
                        notEmpty: {
                            message: 'Поле не может быть пустым'
                        }
                    }
                }
            },
            submitHandler: function(form) {
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
                        if(response.success) {
                            var messageHtml = '<div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'+ response.message +'</div>';
                            $('.message').html(messageHtml);
                            $form.trigger('reset');
                            var inputHtml = '<input value="" class="form-control" placeholder="" name="tags[1]" id="tags[1]" type="text">';
                            $('.original-tags').html('');
                            $('.original-tags').append('<div class="input first">' + inputHtml + '</div>');
                            $('.has-success').removeClass('has-success');
                            $('#search-result').html('');
                            inputNumber = 1;
                        } else {
                            $('#resultTag').parent().append('<small class="help-block" data-bv-validator-for="resultTag" data-bv-validator="notEmpty">'+ response.message +'</small>');
                            $('#resultTag').parent().toggleClass("has-error has-success");
                            $('#merge-tags-button').removeAttr('disabled');
                        }
                    }
                });
            }
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
                type: "POST",
                data: {formData: data},
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
                    var inputHtml = '<input value="'+ linkText +'" class="form-control" placeholder="" name="tags['+inputNumber+']" id="tags['+inputNumber+']" type="text">';
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