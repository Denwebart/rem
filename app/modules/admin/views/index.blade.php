@extends('admin::layouts.admin')

@section('content')
<div class="page-head">
    <h1>Административная панель сайта  <small></small></h1>
</div>

<div class="content">
    <div class="row">
        @if(Auth::user()->isAdmin())
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="custom-box palette-alizarin">
                    <h3>
                        <a class="timer" data-start="0" data-from="0" data-to="{{ User::all()->count() }}" data-speed="500" data-refresh-interval="10" href="{{ URL::route('admin.users.index') }}"></a>
                    </h3>
                    <p>Пользователей</p>
                    <i class="fa fa-users"></i>
                </div>
            </div>
        @endif
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="custom-box palette-peter-river">
                <h3>
                    <a class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newQuestions) }}" data-speed="500" data-refresh-interval="10" href="{{ URL::route('admin.pages.index') }}"></a>
                </h3>
                <p>Вопросы, <br> ожидающие модерации</p>
                <i class="fa fa-question"></i>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="custom-box palette-carrot">
                <h3>
                    <a class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newArticles) }}" data-speed="500" data-refresh-interval="10" href="{{ URL::route('admin.pages.index') }}"></a>
                </h3>
                <p>Статьи, <br> ожидающие модерации</p>
                <i class="fa fa-file-text"></i>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="custom-box palette-nephritis">
                <h3>
                    <a class="timer" data-start="0" data-from="0" data-to="{{ count($headerWidget->newComments) }}" data-speed="500" data-refresh-interval="10" href="{{ URL::route('admin.comments.index', ['sortBy' => 'is_published', 'direction' => 'asc']) }}"></a>
                </h3>
                <p>Комментарии, <br> ожидающие модерации</p>
                <i class="fa fa-comment"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="box" style="height: 360px">
                <div class="box-title">
                    <i class="fa fa-envelope"></i>
                    <h3>Quick Post</h3>
                    <div class="pull-right box-toolbar">
                        <a href="#" class="btn btn-link btn-xs remove-box"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="#" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Title"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="post, article, tags, put" data-toggle="tags" />
                        </div>
                        <div>
                            <textarea placeholder="Content" class="form-control" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="box-footer clearfix">
                    <button class="pull-left btn btn-success">Publish <i class="fa fa-arrow-circle-right"></i></button>
                    <button class="pull-right btn btn-default">Reset <i class="fa fa-times"></i></button>
                </div>
            </div>
        </div><!-- /.Left col -->

        <div class="col-lg-4">
            <div class="box" style="height: 360px">
                <div class="box-title">
                    <i class="fa fa-comments-o"></i>
                    <h3>Chat</h3>
                    <div class="pull-right box-toolbar">
                        <a href="#" class="btn btn-link btn-xs" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="#"><i class="fa fa-circle text-success"></i> Online</a></li>
                            <li><a href="#"><i class="fa fa-circle text-danger"></i> Offline</a></li>
                        </ul>
                        <a href="#" class="btn btn-link btn-xs remove-box"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="box-body chat" id="chat-box">
                    <div class="item">
                        <img src="/backend/img/avatar2.jpg" alt="user" class="img-thumbnail"/>
                        <p class="message">
                            <a href="#" class="name">
                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 2:15</small>
                                Jill Doe
                            </a>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        </p>
                    </div>
                    <div class="item">
                        <img src="/backend/img/avatar.jpg" alt="user image" class="img-thumbnail"/>
                        <p class="message">
                            <a href="#" class="name">
                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 5:15</small>
                                John Doe
                            </a>

                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        </p>
                    </div>
                    <div class="item">
                        <img src="/backend/img/avatar3.jpg" alt="user image" class="img-thumbnail"/>
                        <p class="message">
                            <a href="#" class="name">
                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 5:30</small>
                                Jeremy Doe
                            </a>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        </p>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="input-group">
                        <input class="form-control" placeholder="Send a message..."/>
                        <div class="input-group-btn">
                            <button class="btn btn-success"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="box" style="height: 360px">
                <div class="box-title">
                    <i class="fa fa-check-square-o"></i>
                    <h3>ToDo List</h3>
                    <div class="pull-right box-toolbar">
                        <a href="#" class="btn btn-link btn-xs remove-box"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="box-body">
                    <ul class="todo">
                        <li class="check">
                            <input type="checkbox" id="checkbox" />
                            <span class="text">Write new article</span>
                            <small class="label label-info"><i class="fa fa-clock-o"></i> 1 mins</small>
                        </li>
                        <li>
                            <input type="checkbox"/>
                            <span class="text">Test new item page</span>
                            <small class="label label-warning"><i class="fa fa-clock-o"></i> 3 mins</small>
                        </li>
                        <li>
                            <input type="checkbox"/>
                            <span class="text">Create new plugins for theme</span>
                            <small class="label label-danger"><i class="fa fa-clock-o"></i> 42 mins</small>
                        </li>
                        <li>
                            <input type="checkbox"/>
                            <span class="text">Check mailbox and new mails</span>
                            <small class="label label-default"><i class="fa fa-clock-o"></i> 3 hours</small>
                        </li>
                        <li>
                            <input type="checkbox"/>
                            <span class="text">Setup the new theme</span>
                            <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 day</small>
                        </li>
                        <li>
                            <input type="checkbox"/>
                            <span class="text">Buy a goat</span>
                            <small class="label label-success"><i class="fa fa-clock-o"></i> 1 week</small>
                        </li>
                    </ul>
                </div>
                <div class="box-footer clearfix no-border no-padding-top">
                    <button class="btn btn-block btn-danger">See all tasks</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop