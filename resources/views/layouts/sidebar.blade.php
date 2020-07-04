<div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if (Auth::user()->isOperator())
            <li class="nav-item">
                <a href="{{ route('patients.index') }}" class="nav-link">
                    <i class="nav-icon fa fa-dashboard"></i>
                    <p>
                        {{ __('patient.pages.list') }}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('sharekeys.index') }}" class="nav-link">
                    <i class="nav-icon fa fa-users"></i>
                    <p>
                        {{ __('sharekey.pages.index') }}
                    </p>
                </a>
            </li>
            @endif
            @if (Auth::user()->isAdmin())
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