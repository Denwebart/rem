<h2>Предпросмотр</h2>

<section id="content">

    <div class="well">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-9">
                        <h2>
                            {{ $page->title }}
                        </h2>
                    </div>
                    <div class="col-md-3">
                        {{-- Рейтинг --}}
                        @include('widgets.rating')
                    </div>
                </div>

                <div class="page-info">
                    <div class="pull-left">
                        <div class="user pull-left">
                            <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                                {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                                <span class="login pull-left">{{ $page->user->login }}</span>
                            </a>
                        </div>
                        <div class="date pull-left" title="Дата публикации">
                            <span class="icon mdi-action-today"></span>
                            <span>{{ DateHelper::dateFormat($page->published_at) }}</span>
                        </div>
                    </div>
                    <div class="pull-right">
                        <div class="answers-count pull-left" title="Количество ответов">
                            <span class="icon mdi-communication-forum"></span>
                            <a href="#answers">
                                <span class="count-comments">
                                    {{ count($page->publishedAnswers) }}
                                </span>
                            </a>
                        </div>

                        <div class="views pull-left" title="Количество просмотров">
                            <span class="icon mdi-action-visibility"></span>
                            <span>{{ $page->views }}</span>
                        </div>

                    </div>
                </div>

                <div class="content">
                    <a class="fancybox" rel="group-content" href="{{ $page->image }}">
                        <img src="{{ $page->image }}" alt=""/>
                    </a>
                    {{ $page->getContentWithWidget() }}
                </div>
            </div>
        </div>
    </div>

    <a href="javascript:void(0)" class="btn btn-primary preview-edit">Редактировать</a>
    <a href="javascript:void(0)" class="btn btn-success preview-save">Сохранить</a>

</section>