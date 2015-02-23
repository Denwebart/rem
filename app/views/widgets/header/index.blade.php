<header id="header-widget">
    <a href="{{ URL::to('admin') }}" class="logo"><i class="fa fa-bolt"></i> <span>Админка</span></a>
    <nav class="navbar navbar-static-top">
        @if(Request::is('admin*'))
            <a href="#" class="navbar-btn sidebar-toggle">
                <span class="sr-only">Навигация</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-header">
                <form role="search" class="navbar-form" method="post" action="#">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Поиск..."/>
                        <span class="input-group-btn">
                            <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
            </div>
        @endif
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown dropdown-notifications">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i><span class="label label-warning">5</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><i class="fa fa-bell"></i>  You have 5 new notifications</li>
                        <li>
                            <ul>
                                <li><a href="#"><i class="fa fa-users success"></i> New user registered</a></li>
                                <li><a href="#"><i class="fa fa-heart info"></i> Jane liked your post</a></li>
                                <li><a href="#"><i class="fa fa-envelope warning"></i> You got a message from Jean</a></li>
                                <li><a href="#"><i class="fa fa-info success"></i> Privacy settings have been changed</a></li>
                                <li><a href="#"><i class="fa fa-comments danger"></i> New comments waiting approval</a></li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all notification</a></li>
                    </ul>
                </li>

                {{ $letters }}

                <li class="dropdown dropdown-tasks">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-tasks"></i><span class="label label-danger">1</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><i class="fa fa-tasks"></i>  You have 1 new task</li>
                        <li>
                            <ul>
                                <li>
                                    <a href="#">
                                        <h3>PHP Developing<small class="pull-right">32%</small></h3>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" style="width: 32%" role="progressbar" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <h3>Database Repair<small class="pull-right">14%</small></h3>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-warning" style="width: 14%" role="progressbar" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <h3>Backup Create<small class="pull-right">65%</small></h3>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-info" style="width: 65%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <h3>Create New Modules<small class="pull-right">80%</small></h3>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-danger" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown widget-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/backend/img/avatar.jpg" class="pull-left" alt="image" />
                        <span>{{ Auth::user()->login }} <i class="fa fa-caret-down"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-cog"></i>Настройки</a>
                        </li>
                        <li>
                            <a href="profile.html"><i class="fa fa-user"></i>Профиль</a>
                        </li>
                        <li class="footer">
                            <a href="{{ URL::to('users/logout') }}"><i class="fa fa-power-off"></i>Выход</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>