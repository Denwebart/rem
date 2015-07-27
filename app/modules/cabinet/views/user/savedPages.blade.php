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
                <h2>{{ $title }}</h2>

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
                                        @include('cabinet::user.pageInfo', ['page' => $page->page, 'item' => $page])
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
                                                <a href="javascript:void(0)" class="pull-right remove-page" data-id="{{ $page->page_id }}" title="Убрать статью из сохраненного" data-toggle="tooltip" data-placement="top">
                                                    <i class="icon mdi-content-archive"></i>
                                                </a>
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
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

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
                        $('[data-page-id=' + pageId + ']').remove();
                    }
                }
            });
        });
    </script>
@endsection
