<div class="col-md-7">
    <div class="box">
        <div class="box-title" style="padding: 7px 10px">
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
                {{ Form::label('parent_id', 'Категория', ['class' => 'control-label']) }}
                {{ Form::select('parent_id', Page::getQuestionsCategory(), $page->parent_id, ['class' => 'form-control']) }}
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
                        {{ Form::label('image', 'Изображение') }}<br/>
                        {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                        {{ $errors->first('image') }}
                        @if($page->image_alt)
                            <img src="" alt=""/>
                            {{ HTML::image($page->image_alt) }}
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
                        <h4>Похожие статьи</h4>
                        <ul class="related-articles">
                            @foreach($page->relatedArticles as $key => $articles)
                                <li data-key="{{ $key }}">
                                    {{ Form::hidden("relatedArticles[$key]", $articles->id) }}
                                    <a href="{{ URL::to($articles->getUrl()) }}">
                                        {{ $articles->getTitle() }}
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-circle">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <h4>Похожие вопросы</h4>
                        <ul class="related-questions">
                            @foreach($page->relatedQuestions as $key => $question)
                                <li data-key="{{ $key }}">
                                    {{ Form::hidden("relatedQuestions[$key]", $question->id) }}
                                    <a href="{{ URL::to($question->getUrl()) }}">
                                        {{ $question->getTitle() }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            <h3>Текст вопроса</h3>
        </div>
        <div class="box-body">

            <div class="form-group">
                {{ Form::textarea('content', $page->content, ['class' => 'form-control editor']) }}
                {{ $errors->first('content') }}
            </div>

            {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
            <a href="{{ URL::route('admin.pages.index') }}" class="btn btn-primary">Отмена</a>
        </div>
    </div>
</div>

@section('style')
    <link rel="stylesheet" href="/backend/css/datepicker/datepicker.css" />
@stop

@section('script')
    @parent

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();
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

            $('#is_published').on('ifChecked', function(event){
//                var nowTemp = new Date();
//                var nowDate = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
//                var nowTime = nowTemp.getHours() + ':' + nowTemp.getMinutes() + ':' + nowTemp.getSeconds();

//                $('#published_at').datepicker('setValue', nowDate);
//                $('#published_at_time').text(nowTime);
            });
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

        });
    </script>

@stop