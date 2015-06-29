<div class="col-md-7">
    <div class="box">
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('title', 'Заголовок') }}
                {{ Form::text('title', $advertising->title, ['class' => 'form-control']) }}
                {{ $errors->first('title') }}
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-5">
                        {{ Form::label('description', 'Описание') }}
                        {{ Form::textarea('description', $advertising->description, ['class' => 'form-control']) }}
                        {{ $errors->first('description') }}
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            {{ Form::label('code', 'HTML/JavaScript') }}
                            {{ Form::textarea('code', $advertising->code, ['class' => 'form-control']) }}
                            {{ $errors->first('code') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('is_active', 'Включен') }}
                {{ Form::hidden('is_active', 0, ['id' => 'is_active_uncheck']) }}
                {{ Form::checkbox('is_active', 1) }}
            </div>
            <div class="form-group">
                {{ Form::label('is_show_title', 'Отображать заголовок') }}
                {{ Form::hidden('is_show_title', 0, ['id' => 'is_published_uncheck']) }}
                {{ Form::checkbox('is_show_title', 1) }}
            </div>
            <div class="form-group">
                <h4>Доступно</h4>
                {{ Form::radio('access', Advertising::ACCESS_FOR_ALL, true, ['id' => 'access-all', 'class'=>'radio']) }}
                {{ Form::label('access-all', Advertising::$access[Advertising::ACCESS_FOR_ALL]) }}

                {{ Form::radio('access', Advertising::ACCESS_FOR_REGISTERED, false, ['id' => 'access-registered', 'class'=>'radio']) }}
                {{ Form::label('access-registered', Advertising::$access[Advertising::ACCESS_FOR_REGISTERED]) }}

                {{ Form::radio('access', Advertising::ACCESS_FOR_GUEST, false, ['id' => 'access-guest', 'class'=>'radio']) }}
                {{ Form::label('access-guest', Advertising::$access[Advertising::ACCESS_FOR_GUEST]) }}
            </div>
        </div>
    </div>
</div>

<div class="col-md-5">

    <div class="box">
        <div class="box-title">
            <h3>Область</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div id="areas">
                    {{ Form::hidden('area', $advertising->area, ['id' => 'area']) }}
                    <div class="col-xs-3" style="padding-right: 5px">
                        <div class="area{{ (Advertising::AREA_LEFT_SIDEBAR == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_LEFT_SIDEBAR }}">
                            <p class="area-title">
                                {{ Advertising::$areas[Advertising::AREA_LEFT_SIDEBAR] }}
                            </p>
                            <span class="area-size">(max 325px)</span>
                        </div>
                    </div>
                    <div class="col-xs-6" style="padding: 0 5px">
                        <h5>Заголовок страницы</h5>
                        <div class="area{{ (Advertising::AREA_CONTENT_TOP == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_CONTENT_TOP }}">
                            <p class="area-title">
                                {{ Advertising::$areas[Advertising::AREA_CONTENT_TOP] }}
                            </p>
                            <span class="area-size">(max 620px)</span>
                        </div>
                        <p>Текст страницы</p>
                        <div class="area{{ (Advertising::AREA_CONTENT_MIDDLE == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_CONTENT_MIDDLE }}">
                            <p class="area-title">
                                {{ Advertising::$areas[Advertising::AREA_CONTENT_MIDDLE] }}
                            </p>
                            <span class="area-size">(max 620px)</span>
                        </div>
                        <p>Статьи или комментарии</p>
                        <div class="area{{ (Advertising::AREA_CONTENT_BOTTOM == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_CONTENT_BOTTOM }}">
                            <p class="area-title">
                                {{ Advertising::$areas[Advertising::AREA_CONTENT_BOTTOM] }}
                            </p>
                            <span class="area-size">(max 620px)</span>
                        </div>
                    </div>
                    <div class="col-xs-3" style="padding-left: 5px">
                        <div class="area{{ (Advertising::AREA_RIGHT_SIDEBAR == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_RIGHT_SIDEBAR }}">
                            <p class="area-title">
                                {{ Advertising::$areas[Advertising::AREA_RIGHT_SIDEBAR] }}
                            </p>
                            <span class="area-size">(max 325px)</span>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="area{{ (Advertising::AREA_SITE_BOTTOM == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_SITE_BOTTOM }}">
                            <p class="area-title">
                                {{ Advertising::$areas[Advertising::AREA_SITE_BOTTOM] }}
                            </p>
                            <span class="area-size">(max 1300px)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    {{ Form::hidden('backUrl', $backUrl) }}

    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
</div>

@section('script')
    @parent

    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replace('text')
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

    <!-- Select area -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#areas').on('click', '.area', function(){
                $('#areas .area').removeClass('selected-area');
                $(this).addClass('selected-area');
                $('#area').val($(this).data('area'));
            });
        });
    </script>

@stop