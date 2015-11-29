@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-home"></i>
            Административная панель сайта
            <small></small>
        </h1>
    </div>
    <div class="content">
        <div class="row">
            @if(Auth::user()->isAdmin())
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <a class="custom-box palette-alizarin" href="{{ URL::route('admin.users.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}">
                        <h3>
                            <span class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newUsers) }}" data-speed="500" data-refresh-interval="10"></span>
                        </h3>
                        <span>Новые пользователи</span>
                        <i class="fa fa-users"></i>
                    </a>
                </div>
            @endif
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a class="custom-box palette-peter-river" href="{{ URL::route('admin.questions.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}">
                    <h3>
                        <span class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newQuestions) }}" data-speed="500" data-refresh-interval="10"></span>
                    </h3>
                    <span>Новые вопросы</span>
                    <i class="fa fa-question"></i>
                </a>
            </div><!-- ./col -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a class="custom-box palette-carrot" href="{{ URL::route('admin.articles.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}">
                    <h3>
                        <span class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newArticles) }}" data-speed="500" data-refresh-interval="10"></span>
                    </h3>
                    <span>Новые статьи</span>
                    <i class="fa fa-file-text"></i>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a class="custom-box palette-nephritis" href="{{ URL::route('admin.comments.index', ['sortBy' => 'created_at', 'direction' => 'desc']) }}">
                    <h3>
                        <span class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newComments) + count($headerWidget->newAnswers) }}" data-speed="500" data-refresh-interval="10"></span>
                    </h3>
                    <span>Новые комментарии и ответы</span>
                    <i class="fa fa-comment"></i>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="box" id="cache">
                    <div class="box-title">
                        <h3>Кэш сайта</h3>
                    </div>
                    <div class="box-body">
                        Количество файлов/папок:
                        <span class="files-count">
                            {{ $cacheSize['filesCount'] }}
                        </span>
                        <br>
                        Вес:
                        <span class="files-size">
                            {{ StringHelper::fileSize($cacheSize['filesSize']) }}
                        </span>
                    </div>
                    <div class="box-footer">
                        <a href="javascript:void(0)" class="btn btn-success btn-block" id="clear-cache">
                            Очистить кэш
                        </a>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        (function($) {
            // number count
            $('.timer').countTo();
        })(jQuery);

        $('#clear-cache').on('click', function(){
            $.ajax({
                url: '{{ URL::route('admin.cache.clear') }}',
                dataType: "text json",
                type: "POST",
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $('#site-messages').prepend(response.message);
                        setTimeout(function() {
                            hideSiteMessage($('.site-message'));
                        }, 2000);

                        $('#cache').find('.files-count').text(0);
                        $('#cache').find('.files-size').text(0);
                    }
                }
            });
        });
    </script>
@stop