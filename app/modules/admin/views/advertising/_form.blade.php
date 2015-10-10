<div class="col-md-7">
    <div class="box">
        <div class="box-body">
            <div class="form-group">
                {{ Form::label('title', 'Заголовок') }}
                {{ Form::text('title', $advertising->title, ['class' => 'form-control']) }}
                {{ $errors->first('title') }}
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        {{ Form::label('description', 'Описание') }}
                        {{ Form::textarea('description', $advertising->description, ['class' => 'form-control']) }}
                        {{ $errors->first('description') }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('position', 'Позиция') }}
                        {{ Form::number('position', $advertising->position, ['class' => 'form-control']) }}
                        {{ $errors->first('position') }}
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="types">
                        <div class="form-group">
                            {{ Form::radio('type', Advertising::TYPE_ADVERTISING, (Advertising::TYPE_ADVERTISING == $advertising->type || is_null($advertising->type)) ? true : false, ['id' => 'type-advertising', 'class'=>'radio', (Request::is('admin/advertising/*/edit') && Advertising::TYPE_ADVERTISING != $advertising->type) ? 'disabled' : '']) }}
                            {{ Form::label('type-advertising', Advertising::$types[Advertising::TYPE_ADVERTISING], ['class' => (Request::is('admin/advertising/*/edit') && Advertising::TYPE_ADVERTISING != $advertising->type) ? 'disabled' : '']) }}

                            {{ Form::radio('type', Advertising::TYPE_WIDGET, (Advertising::TYPE_WIDGET == $advertising->type) ? true : false, ['id' => 'type-widget', 'class'=>'radio', (Request::is('admin/advertising/*/edit') && Advertising::TYPE_WIDGET != $advertising->type) ? 'disabled' : '']) }}
                            {{ Form::label('type-widget', Advertising::$types[Advertising::TYPE_WIDGET], ['class' => (Request::is('admin/advertising/*/edit') && Advertising::TYPE_WIDGET != $advertising->type) ? 'disabled' : '']) }}
                        </div>
                        <div class="form-group advertising" style="display: none">
                            {{ Form::label('code-advertising', 'HTML/JavaScript') }}
                            {{ Form::textarea('code-advertising', $advertising->code, ['class' => 'form-control']) }}
                            {{ $errors->first('code') }}
                        </div>
                        <div class="form-group widget" style="display: none">
                            {{ Form::label('code-widget', 'Выберите виджет') }}
                            {{ Form::select('code-widget', ['' => '-'] + Advertising::$widgets, $advertising->code, ['class' => 'form-control']) }}
                            {{ $errors->first('code') }}

                            {{ Form::label('limit', 'Количество элементов') }}
                            {{ Form::number('limit', $advertising->limit, ['class' => 'form-control']) }}
                            {{ $errors->first('limit') }}
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

    <div class="box" id="widget">
        <div class="box-body">
            <div class="form-group" id="pages-types">
                <h4>Выберите страницу</h4>
                <div class="row">
                    @foreach(AdvertisingPage::$pages as $pageTypeKey => $pageTypeValue)
                        <div class="col-md-6">
                            {{ Form::checkbox('pages['.$pageTypeKey.']', 1, isset($pages[$pageTypeKey]) ? true : false) }}
                            {{ Form::label('pages['.$pageTypeKey.']', $pageTypeValue) }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="box-title toggle-buttons">
            <div class="col-xs-12">
                <a href="javascript:void(0)" class="btn show-tab active" data-id="three-columns">3 колонки</a>
                <a href="javascript:void(0)" class="btn show-tab" data-id="two-columns">2 колонки</a>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div id="areas">
                    <!-- Области для страниц с 3-мя колонками -->
                    {{ Form::hidden('area', $advertising->area, ['id' => 'area']) }}
                    <div id="three-columns" class="tab">
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
                            <div class="area area-not-for-widget{{ (Advertising::AREA_CONTENT_TOP == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_CONTENT_TOP }}">
                                <p class="area-title">
                                    {{ Advertising::$areas[Advertising::AREA_CONTENT_TOP] }}
                                </p>
                                <span class="area-size">(max 620px)</span>
                            </div>
                            <p>Текст страницы</p>
                            <div class="area area-not-for-widget{{ (Advertising::AREA_CONTENT_MIDDLE == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_CONTENT_MIDDLE }}">
                                <p class="area-title">
                                    {{ Advertising::$areas[Advertising::AREA_CONTENT_MIDDLE] }}
                                </p>
                                <span class="area-size">(max 620px)</span>
                            </div>
                            <p>Статьи или комментарии</p>
                            <div class="area area-not-for-widget{{ (Advertising::AREA_CONTENT_BOTTOM == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_CONTENT_BOTTOM }}">
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
                            <div class="area area-not-for-widget{{ (Advertising::AREA_SITE_BOTTOM == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_SITE_BOTTOM }}">
                                <p class="area-title">
                                    {{ Advertising::$areas[Advertising::AREA_SITE_BOTTOM] }}
                                </p>
                                <span class="area-size">(max 1300px)</span>
                            </div>
                        </div>
                    </div>
                    <!-- Области для страниц с 2-мя колонками -->
                    <div id="two-columns" class="tab" style="display: none;">
                        <div class="col-xs-4" style="padding-right: 5px">
                            <div class="area{{ (Advertising::AREA_LEFT_SIDEBAR == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_LEFT_SIDEBAR }}">
                                <p class="area-title">
                                    {{ Advertising::$areas[Advertising::AREA_LEFT_SIDEBAR] }}
                                </p>
                                <span class="area-size">(max 325px)</span>
                            </div>
                        </div>
                        <div class="col-xs-8" style="padding: 0 5px">
                            <h5>Заголовок страницы</h5>
                            <p>Контент страницы</p>
                            <div class="area area-not-for-widget{{ (Advertising::AREA_CONTENT_BOTTOM == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_CONTENT_BOTTOM }}">
                                <p class="area-title">
                                    {{ Advertising::$areas[Advertising::AREA_CONTENT_BOTTOM] }}
                                </p>
                                <span class="area-size">(max 620px)</span>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="area area-not-for-widget{{ (Advertising::AREA_SITE_BOTTOM == $advertising->area) ? ' selected-area' : '' }}" data-area="{{ Advertising::AREA_SITE_BOTTOM }}">
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
</div>

<div class="col-md-12">
    {{ Form::hidden('backUrl', $backUrl) }}

    {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
</div>

@section('script')
    @parent

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
                if(!$(this).hasClass('not-active')) {
                    if (!$(this).hasClass('selected-area')) {
                        $('#areas .area').removeClass('selected-area');
                        $(this).addClass('selected-area');
                        $('#areas').find('[data-area=' + $(this).data('area') + ']').addClass('selected-area');
                        $('#area').val($(this).data('area'));
                    } else {
                        $('#areas .area').removeClass('selected-area');
                        $('#area').val('');
                    }
                }
            });

            if($("#type-advertising").is(":checked")) {
                $('.types .widget').hide();
                $('.types .advertising').show();
            } else if($("#type-widget").is(":checked")) {
                $('.types .advertising').hide();
                $('.types .widget').show();
                $('.area.area-not-for-widget').addClass('not-active');
            }

            $('#type-widget').on('ifToggled', function() {
                $('.types .advertising').hide();
                $('.types .widget').show();
                $('.area.area-not-for-widget').addClass('not-active');
                $('#area').val('');
                $('#areas .area').removeClass('selected-area');
            });
            $('#type-advertising').on('ifToggled', function() {
                $('.types .widget').hide();
                $('.types .advertising').show();
                $('.area.area-not-for-widget').removeClass('not-active');
            });

            $('.show-tab').on('click', function() {
                $('.show-tab').removeClass('active');
                $(this).addClass('active');
                $('.tab').hide();
                $('#' + $(this).data('id')).show();
            });

        });
    </script>

@stop