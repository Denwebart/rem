@extends('admin::layouts.admin')

@section('content')
<div class="page-head">
    <h1>Объединение тегов
        <small>слияние похожих тегов</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin') }}">Главная</a></li>
        <li><a href="{{ URL::route('admin.tags.index') }}">Теги</a></li>
        <li class="active">Объединение тегов</li>
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
                                <div class="form-group">
                                    <div class="original-tags">
                                        {{--{{ Form::label('tags[1]', 'Тег', ['class' => 'col-sm-2 control-label']) }}--}}
                                        <div class="input first">
                                            {{ Form::text('tags[1]', null, ['class' => 'form-control', 'placeholder' => '', 'disabled' => 'disabled', 'id' => 'tags[1]']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <span>объединить в</span>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="image"></div>
                                    {{ Form::text('resultTag', null, ['class' => 'form-control autocomplete', 'placeholder' => '']) }}
                                    {{ $errors->first('resultTag') }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                {{ Form::submit('Объединить', ['class' => 'btn btn-success margin-top-25']) }}
                            </div>

                        {{ Form::close() }}
                    </div>

                    <hr/>

                    <div class="row">
                        {{ Form::open(['method' => 'POST', 'route' => ['admin.tags.search'], 'id' => 'search-tags-form', 'class' => 'form-inline']) }}

                        <div class="form-group">
                            <div class="image"></div>
                            {{ Form::label('search', 'Поиск тега', ['class' => 'col-sm-2 control-label']) }}
                            {{ Form::text('search', null, ['class' => 'form-control', 'placeholder' => '']) }}
                            {{ $errors->first('search') }}
                        </div>

                        {{ Form::submit('Найти', ['class' => 'btn btn-success margin-top-25']) }}

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
    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
    <script src="/js/jquery-ui.min.js"></script>
@stop

@section('script')
    @parent

    <script type="text/javascript">

        $('#merge-tags-form, #search-tags-form').trigger('reset');

        $(".autocomplete").autocomplete({
            source: "<?php echo URL::route('admin.tags.autocomplete') ?>",
            minLength: 2,
            select: function(e, ui) {
                $(this).val(ui.item.value);
                $("#merge-tags-form").find('.error').empty();
            }
        });

        // объединение тегов
        $("#merge-tags-form").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            var posting = $.post(url, { formData: data });
            posting.done(function(response) {
                if(response.success) {
                    var messageHtml = '<div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'+ response.message +'</div>';
                    $('.message').html(messageHtml);
                    $form.trigger('reset');
                }
            }); // done
        });

        // поиск тегов
        $("#search-tags-form").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            var posting = $.post(url, { formData: data });
            posting.done(function(response) {
                if(response.success) {
                    $('#search-result').html(response.resultHtml);
                }
            }); // done
        });

        // занесение выбранного тега в форму
        var inputNumber = 1;
        $("#search-result").on('click', '.add-to-input', function() {
            var $link = $(this);
            var linkText = $link.find('.text').text().trim();
            var deleteInputHtml = '<a type="button" class="btn btn-danger btn-circle delete-input"><i class="glyphicon glyphicon-remove"></i></a>';
            if(inputNumber == 1) {
                $("[id^='tags']").val(linkText);
                $("[id^='tags']").parent().append(deleteInputHtml);
            } else {
                var plusHtml = '<button type="button" class="btn btn-default btn-circle btn-outline plus"><i class="glyphicon glyphicon-plus"></i></button>';
                var inputHtml = '<input value="'+ linkText +'" class="form-control autocomplete ui-autocomplete-input" placeholder="" name="tags['+inputNumber+']" id="tags['+inputNumber+']" type="text" disabled="disabled">';
                $('.original-tags').append('<div class="input">' + plusHtml + inputHtml + deleteInputHtml + '</div>');
            }
            inputNumber++;
            $link.remove();
        });

        $(".original-tags").on('click', '.delete-input', function() {
            var $deleteLink = $(this);
            if(!$deleteLink.parent().hasClass('first')) {
                $deleteLink.parent().remove();
                inputNumber--;
            } else {
                if(inputNumber <= 2) {
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