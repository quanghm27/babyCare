<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="/images/user.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="/users/{{ Auth::user()->id }}/edit" class="d-block">{{ Auth::user()->name }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if (Auth::user()->isOperator())
            <li class="nav-item">
                <a href="{{ route('sharekeys.index') }}" class="nav-link">
                    <i class="nav-icon fa fa-dashboard"></i>
                    <p>
                        {{ __('sharekey.pages.index') }}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('patients.index') }}" class="nav-link">
                    <i class="nav-icon fa fa-dashboard"></i>
                    <p>
                        {{ __('patient.pages.list') }}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('patients.create') }}" class="nav-link">
                    <i class="nav-icon fa fa-plus"></i>
                    <p>
                        {{ __('patient.pages.create') }}
                    </p>
                </a>
            </li>
            @endif
            @if (Auth::user()->isAdmin())
            <li class="nav-item">
                <a href="{{ route('departments.index') }}" class="nav-link">
                    <i class="nav-icon fa fa-hospital-o"></i>
                    <p>
                        {{ __('department.pages.index') }}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="nav-icon fa fa-users"></i>
                    <p>
                        {{ __('user.pages.index') }}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('users.create') }}" class="nav-link">
                    <i class="nav-icon fa fa-user-plus"></i>
                    <p>
                        {{ __('user.pages.create') }}
                    </p>
                </a>
            </li>
            @endif
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>