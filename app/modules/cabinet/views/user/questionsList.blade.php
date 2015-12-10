<?php $data = isset($data) ? $data : (Request::all() ? Request::all() : []); ?>
@if(count($questions))
    @foreach($questions as $question)
        <div class="well item @if(!$question->is_published) not-published @endif" data-question-id="{{ $question->id }}">
            <div class="row">
                @if(!$question->is_published)
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="not-published-text pull-right margin-bottom-10">
                            Ожидает модерации
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="date pull-left hidden-lg hidden-md hidden-sm">
                                <i class="material-icons pull-left">today</i>
                                <span class="pull-left">{{ DateHelper::dateFormat($question->published_at) }}</span>
                            </div>
                            <div class="page-info">
                                <div class="date pull-left hidden-xs">
                                    <i class="material-icons">today</i>
                                    <span>{{ DateHelper::dateFormat($question->published_at) }}</span>
                                </div>
                                <div class="pull-right">
                                    <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip">
                                        <i class="material-icons">visibility</i>
                                        <span>{{ $question->views }}</span>
                                    </div>
                                    <div class="saved-count pull-left" title="Сколько пользователей сохранили" data-toggle="tooltip">
                                        <i class="material-icons">archive</i>
                                        <span>{{ count($question->whoSaved) }}</span>
                                    </div>
                                    <div class="rating pull-left" title="Рейтинг (количество проголосовавших)" data-toggle="tooltip" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                        <i class="material-icons">grade</i>
                                                                <span>
                                                                    <span itemprop="ratingValue">{{ $question->getRating() }}</span>
                                                                    <meta itemprop="ratingCount" content="{{ $question->votes }}" />
                                                                    (
                                                                    <span itemprop="reviewCount">{{ $question->voters }}</span>
                                                                    )
                                                                </span>
                                    </div>
                                    <div class="subscribers pull-left" title="Количество подписавшихся на вопрос" data-toggle="tooltip">
                                        <i class="material-icons">local_library</i>
                                        <span>{{ count($question->subscribers) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 col-xs-9">
                            <h3>
                                <a href="{{ URL::to($question->getUrl()) }}">
                                    {{ $question->title }}
                                </a>
                            </h3>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <div class="answers-text">
                                <span>Ответов:</span>
                            </div>
                            <div class="answers-value">
                                <a href="{{ URL::to($question->getUrl()) }}#answers" class="count @if(count($question->bestComments)) best @endif">
                                    {{ count($question->publishedAnswers) }}
                                </a>
                                @if(count($question->bestComments))
                                    <a href="{{ URL::to($question->getUrl()) }}#answers">
                                        <i class="material-icons mdi-success" title="Есть решение" data-toggle="tooltip">done</i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-9 col-xs-8">
                            <div class="category">
                                <div class="text pull-left hidden-xs">
                                    Категория:
                                </div>
                                <div class="link pull-left">
                                    <a href="{{ URL::to($question->parent->getUrl()) }}">
                                        {{ $question->parent->getTitle() }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-4">
                            @if(Auth::check())
                                @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                                    <div class="buttons pull-right">
                                        @if(Auth::user()->isAdmin())
                                            <a href="javascript:void(0)" class="pull-right delete-question" data-id="{{ $question->id }}" title="Удалить вопрос" data-toggle="tooltip" data-placement="top">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        @endif
                                        <a href="{{ URL::route('admin.questions.edit', ['id' => $question->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать вопрос" data-toggle="tooltip">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </div>
                                @elseif((Auth::user()->is($question->user) && !Ip::isBanned() && !Auth::user()->is_banned && $question->isEditable()) || Auth::user()->isAdmin())
                                    <div class="buttons pull-right">
                                        <a href="javascript:void(0)" class="pull-right delete-question" data-id="{{ $question->id }}" title="Удалить вопрос" data-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">delete</i>
                                        </a>
                                        <a href="{{ URL::route('user.questions.edit', ['login' => $question->user->getLoginForUrl(),'id' => $question->id, 'backUrl' => urlencode(Request::url())]) }}" class="pull-right" title="Редактировать вопрос" data-toggle="tooltip">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    @if(Auth::check())
        @if(Auth::user()->is($user))
            <p>
                Вы еще не задали ни одного вопроса.
            </p>
        @else
            <p>
                Вопросов нет.
            </p>
        @endif
    @else
        <p>
            Вопросов нет.
        </p>
    @endif
@endif
{{ $questions->appends($data)->links() }}