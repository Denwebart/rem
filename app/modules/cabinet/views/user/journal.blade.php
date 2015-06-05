@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мой бортовой журнал' : 'Бортовой журнал пользователя ' . $user->login) : 'Бортовой журнал пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login }}
                    </a>
                </li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>
        </div>
        <div class="col-lg-9">
            <h2>{{ $title }}</h2>

            @if(Auth::check())
                @if(Auth::user()->is($user))
                    <a href="{{ URL::route('user.journal.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                        Написать статью
                    </a>
                @endif
            @endif

            <div id="articles">

                @foreach($articles as $article)

                    <div data-article-id="{{ $article->id }}" class="col-md-12">
                        <div class="well">
                            @if(Auth::check())
                                @if(Auth::user()->is($user) || Auth::user()->isAdmin())
                                    <div class="pull-right">
                                        <a href="{{ URL::route('user.journal.edit', ['login' => $user->getLoginForUrl(),'id' => $article->id]) }}" class="btn btn-info">
                                            Редактировать
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger delete-article" data-id="{{ $article->id }}">
                                            Удалить
                                        </a>
                                    </div>
                                @endif
                            @endif
                            <h3>
                                <a href="{{ URL::to($article->getUrl()) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <div class="date date-create">{{ $article->created_at }}</div>

                            <div>
                                {{ $article->getIntrotext() }}
                            </div>

                            <ul class="tags">
                                @foreach($article->tags as $tag)
                                    <li>
                                        <a href="{{ URL::route('search', ['tag' => $tag->title]) }}" title="{{ $tag->title }}">
                                            {{ $tag->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="status">
                                Статус:
                                {{ ($article->is_published) ? 'Опубликована' : 'Ожидает модерации' }}
                            </div>

                        </div>
                    </div>

                @endforeach

                <div>
                    {{ $articles->links() }}
                </div>

            </div>

        </div>
    </div>
@stop

@section('script')
    @parent

    <!-- Delete Article -->
    @if(Auth::check())
        @if(Auth::user()->is($user) || Auth::user()->isAdmin())
            <script type="text/javascript">
                $('.delete-article').click(function(){
                    var articleId = $(this).data('id');
                    if(confirm('Вы уверены, что хотите удалить статью?')) {
                        $.ajax({
                            url: '<?php echo URL::route('user.journal.delete', ['login' => $user->getLoginForUrl()]) ?>',
                            dataType: "text json",
                            type: "POST",
                            data: {articleId: articleId},
                            success: function(response) {
                                if(response.success){
                                    $('[data-article-id=' + articleId + ']').remove();
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endif
@stop