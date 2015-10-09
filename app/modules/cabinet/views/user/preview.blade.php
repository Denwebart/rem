<div class="row">
    <div class="col-md-12">
        <h2>Предпросмотр</h2>
    </div>
    <div class="col-md-12">
        <div class="buttons pull-right margin-bottom-20">
            <a href="javascript:void(0)" class="btn btn-sm btn-primary preview-edit">Редактировать</a>
            <a href="javascript:void(0)" class="btn btn-sm btn-success preview-save">Сохранить</a>
        </div>
    </div>
</div>

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
                        <div id="rating" class="rating pull-right">
                            <div id="rate-votes">{{ $page->getRating() }}</div>
                            <div id="rate-voters">(голосовавших: <span>{{ $page->voters }}</span>)</div>
                            <div id="rate-stars">
                                <div id="jRate"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="date pull-left hidden-lg hidden-md hidden-sm" title="Дата публикации">
                    <i class="material-icons pull-left">today</i>
                    <span class="pull-left">{{ DateHelper::dateFormat($page->published_at) }}</span>
                </div>

                <div class="page-info">
                    <div class="pull-left">
                        <div class="user pull-left">
                            <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                                {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                                <span class="login hidden-xs pull-left">{{ $page->user->login }}</span>
                            </a>
                        </div>
                        <div class="date pull-left hidden-xs" title="Дата публикации">
                            <i class="material-icons">today</i>
                            <span>{{ DateHelper::dateFormat($page->published_at) }}</span>
                        </div>
                    </div>
                    <div class="pull-right">
                        <div class="views pull-left" title="Количество просмотров">
                            <i class="material-icons">visibility</i>
                            <span>{{ $page->views }}</span>
                        </div>

                        @if(Page::TYPE_QUESTION == $page->type)
                            <div class="answers-count pull-left" title="Количество ответов">
                                <i class="material-icons">question_answer</i>
                                <a href="#answers">
                                    <span class="count-comments">
                                        {{ count($page->publishedAnswers) }}
                                    </span>
                                </a>
                            </div>

                            <div class="subscribers pull-left" title="Количество подписавшихся на вопрос">
                                <i class="material-icons">local_library</i>
                                <span>{{ count($page->subscribers) }}</span>
                            </div>
                        @else
                            <div class="comments-count pull-left" title="Количество комментариев">
                                <i class="material-icons">chat_bubble</i>
                                <a href="#comments">
                                    <span class="count-comments">
                                        {{ count($page->publishedComments) }}
                                    </span>
                                </a>
                            </div>
                        @endif

                        <div class="saved-count pull-left" title="Сколько пользователей сохранили" data-toggle="tooltip" data-placement="top">
                            <i class="material-icons">archive</i>
                            <span>{{ count($page->whoSaved) }}</span>
                        </div>
                    </div>
                </div>

                <div class="content">
                    @if($page->image)
                        <a class="fancybox" rel="group-content" href="{{ $page->image }}">
                            <img src="{{ $page->image }}" alt="{{ $page->title }}" class="img-responsive page-image"/>
                        </a>
                    @endif

                    {{ $page->getContentWithWidget() }}

                    @if(count($page->tags))
                        <ul class="tags">
                            @foreach($page->tags as $tag)
                                <li>
                                    <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}" class="tag btn btn-sm btn-info">
                                        {{ $tag->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>