<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="/images/user.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
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
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>