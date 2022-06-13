<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <ul class="nav in" id="side-menu">
            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                <!-- input-group -->
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search..."> <span class="input-group-btn">
                        <button class="btn btn-default" type="button"> <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                <!-- /input-group -->
            </li>
            <li class="nav-small-cap m-t-10">--- {{ __('Main Menu') }}</li>
            <li>
                <a href="{{ route('admin') }}" class="waves-effect {{ request()->is('admin') ? 'active' : '' }}">
                    <i class="linea-icon linea-basic fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> {{ __('Dashboard') }} </span>
                </a>
            </li>
            <li class="{{ request()->is('admin/post') || request()->is('admin/post/create') ? 'active' : '' }}">
                <a href="javascript:void(0);"
                    class="waves-effect {{ request()->is('admin/post') || request()->is('admin/post/create') ? 'active' : '' }}"><i
                        class="linea-icon linea-basic fa-fw icon-pin text-danger"></i> <span
                        class="hide-menu text-danger"> {{ __('Posts') }} <span class="fa arrow"></span> <span
                            class="label label-rouded label-custom pull-right">02</span></span></a>
                <ul class="nav nav-second-level {{ request()->is('admin/post') || request()->is('admin/post/create') ? 'in' : '' }}">
                    <li> <a href="{{ route('post.index') }}"
                            class="{{ request()->is('admin/post') ? 'active' : '' }}">{{ __('All Posts') }}</a>
                    </li>
                    <li> <a href="{{ route('post.create') }}"
                            class="{{ request()->is('admin/post/create') ? 'active' : '' }}">{{ __('Add New') }}</a>
                    </li>
                </ul>
            </li>
            <li class="{{ request()->is('admin/item') ? 'active' : '' }}">
                <a href="{{ route('item') }}" class="waves-effect {{ request()->is('admin/item') ? 'active' : '' }}">
                    <i data-icon=")" class="linea-icon linea-basic fa-fw"></i>
                    <span class="hide-menu">{{ __('Items Manager') }} </span>
                </a>
            </li>
            <li class="{{ request()->is('admin/task') || request()->is('admin/task/create') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="waves-effect {{ request()->is('admin/task') || request()->is('admin/task/create') ? 'active' : '' }}"><i
                        class="linea-icon linea-basic fa fa-list-alt text-danger"></i> <span
                        class="hide-menu text-danger"> {{ __('Tasks') }} <span class="fa arrow"></span> <span
                            class="label label-rouded label-custom pull-right">02</span></span></a>
                <ul class="nav nav-second-level">
                    <li> <a href="{{ route('task.index') }}" class="{{ request()->is('admin/task') ? 'active' : '' }}">{{ __('All Tasks') }}</a> </li>
                    <li> <a href="{{ route('task.create') }}" class="{{ request()->is('admin/task/create') ? 'active' : '' }}">{{ __('Add New') }}</a> </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('logout') }}" class="waves-effect"
                    onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                    <i class="icon-logout fa-fw"></i><span class="hide-menu"> {{ __('Log out') }}</span>
                </a>
                <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
