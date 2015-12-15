@extends('layouts.main')

@section('breadcrumbs')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
    [
        'title' => $page->getTitleForBreadcrumbs()
    ]
    ]])
@stop

@section('content')
    <section id="content" class="well">

        @if(!Request::has('stranitsa') || Request::get('stranitsa') == 1)
            <div itemscope itemtype="http://schema.org/Article">
                <meta itemprop="datePublished" content="{{ DateHelper::dateFormatForSchema($page->published_at) }}">

                @if($page->is_show_title)
                    <h2 itemprop="headline">{{ $page->title }}</h2>
                @else
                    <meta itemprop="headline" content="{{ $page->getTitle() }}">
                @endif

                {{ $areaWidget->contentTop() }}

                @if($page->content)
                    <div class="content" itemprop="articleBody">
                        @if($page->image)
                            <a class="fancybox pull-left" data-fancybox-group="group-content" href="{{ $page->getImageLink('origin') }}">
                                {{ $page->getImage('origin', ['class' => 'page-image']) }}
                            </a>
                        @else
                            <meta itemprop="image" content="{{ URL::to(Config::get('settings.defaultImage')) }}">
                        @endif
                        {{ $page->getContentWithWidget() }}
                    </div>
                @endif

                {{ $areaWidget->contentMiddle() }}
            </div>
        @endif

        @if(Auth::check())
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-6 col-lg-5 col-sm-offset-8 col-md-offset-6 col-lg-offset-7">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ URL::route('admin.questions.create', ['backUrl' => urlencode(Request::url())]) }}" class="btn btn-success btn-sm btn-full pull-right">
                            Задать вопрос
                        </a>
                    @else
                        <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl(), 'backUrl' => urlencode(Request::url())]) }}" class="btn btn-success btn-sm btn-full pull-right">
                            Задать вопрос
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <section id="questions-area" class="blog margin-top-10">
            <div class="count margin-bottom-20 pull-left">
                @include('count', ['models' => $questions])
            </div>
            <div class="pull-right">
                {{ Form::open(['method' => 'GET', 'route' => ['search.questions'], 'id' => 'filter-form']) }}
                {{ Form::hidden('without-answer', 0, ['id' => 'without-answer']) }}
                {{ Form::hidden('without-best-answer', 0, ['id' => 'without-best-answer']) }}
                <a href="javascript:void(0)" rel="nofollow" data-attr="without-answer" class="filter-link @if(Request::get('without-answer')) active @endif">
                    <span>Без ответов</span>
                </a>
                <a href="javascript:void(0)" rel="nofollow" data-attr="without-best-answer" class="filter-link margin-left-10 @if(Request::get('without-best-answer')) active @endif">
                    <span>Нерешённые</span>
                </a>
                {{ Form::close() }}
            </div>
            <div class="clearfix"></div>
            <div class="list">
                @include('site.questionsList', ['pageId' => $page->id])
            </div>
        </section><!--blog-area-->

        {{ $areaWidget->contentBottom() }}

    </section>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        $('.blog').on('click', '.filter-link', function () {
            var $link = $(this);
            if($link.hasClass('active')) {
                $link.removeClass('active');
                $('#' + $link.data('attr')).val(0);
            } else {
                $link.addClass('active');
                $('#' + $link.data('attr')).val(1);
            }
            $("#filter-form").submit();
        });

        $("form[id^='filter-form']").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    data = $form.serialize(),
                    url = $form.attr('action');
            $.ajax({
                url: url,
                type: "get",
                data: {
                    searchData: data,
                    pageId: '<?php echo $page->id ?>',
                    url: '<?php echo Request::url(); ?>',
                    pageType: '<?php echo $page->type ?>'
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    //to change the browser URL to the given link location
                    window.history.pushState({parent: response.url}, '', response.url);

                    if(response.success) {
                        $('.blog .count').html(response.countHtmL);
                        $('.list').html(response.listHtmL);
                    }
                },
            });
        });
    </script>
@stop