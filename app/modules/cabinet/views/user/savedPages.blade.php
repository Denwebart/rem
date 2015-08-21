@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Сохранённое' : 'Сохранённое пользователем ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">
                <div class="row">
                    <div class="col-md-8">
                        <h2>{{ $title }}</h2>
                    </div>
                    <div class="col-md-4">
                        @if(Auth::user()->is($user))
                            @if(count($pages))
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm pull-right" id="remove-all-pages" title="Удалить все сохраненные страницы" data-toggle="tooltip">
                                    Удалить все
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="list">
                    @if(count($pages))
                        <section id="saved-pages-area" class="blog">
                            <div class="count">
                                Показано сохраненных страниц: <span>{{ $pages->count() }}</span>.
                                Всего: <span>{{ $pages->getTotal() }}</span>.
                            </div>

                            @foreach($pages as $page)
                                @if($page->page)
                                    <div data-page-id="{{ $page->page->id }}" class="well">
                                        <div class="row">
                                            @if(Page::TYPE_QUESTION == $page->page->type)
                                                @include('cabinet::user.questionInfo', ['page' => $page->page, 'item' => $page])
                                            @else
                                                @include('cabinet::user.pageInfo', ['page' => $page->page, 'item' => $page])
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div data-page-id="{{ $page->page_id }}" class="well">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h3></h3>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="buttons">
                                                    @if(Auth::user()->is($user))
                                                        <a href="javascript:void(0)" class="pull-right remove-page" data-id="{{ $page->page_id }}" title="Убрать статью из сохраненного" data-toggle="tooltip" data-placement="top">
                                                            <i class="material-icons">close</i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="date date-saved">
                                                    <div class="date date-saved">
                                                        <span class="text">Сохранено</span>
                                                        <span class="date">{{ DateHelper::dateFormat($page->created_at) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <p>
                                                    Статья, которую вы сохранили, была удалена.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div>
                                {{ $pages->links() }}
                            </div>
                        </section>
                    @else
                        @if(Auth::user()->is($user))
                            <p>
                                Вы еще ничего не сохранили.
                            </p>
                        @else
                            <p>
                                Сохраненных страниц нет.
                            </p>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    @if(Auth::user()->is($user))
        <script type="text/javascript">
            $(".remove-page").on('click', function() {
                var $link = $(this);
                var pageId = $link.data('id');
                $.ajax({
                    url: "{{ URL::route('user.removePage', ['login' => Auth::user()->getLoginForUrl()]) }}",
                    dataType: "text json",
                    type: "POST",
                    data: {pageId: pageId},
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $("#site-messages").prepend(response.message);
                            $('[data-page-id=' + pageId + ']').remove();
                        } else {
                            $('#site-messages').prepend(response.message);
                        }
                    }
                });
            });

            $('#remove-all-pages').on('click', function(){
                var $button = $(this);
                if(confirm('Вы уверены, что хотите удалить все сохраненные страницы?')) {
                    $.ajax({
                        url: "{{ URL::route('user.removeAllPages', ['login' => Auth::user()->getLoginForUrl()]) }}",
                        dataType: "text json",
                        type: "POST",
                        data: {},
                        beforeSend: function (request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        },
                        success: function (response) {
                            if (response.success) {
                                $("#site-messages").prepend(response.message);
                                $button.parent().find('.tooltip').remove();
                                $button.remove();
                                $('#content .list').html('<p>Вы еще ничего не сохранили.</p>');
                            } else {
                                $("#site-messages").prepend(response.message);
                            }
                        }
                    });
                }
            });
        </script>
    @endif
@endsection
