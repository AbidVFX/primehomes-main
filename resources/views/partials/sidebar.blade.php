<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ URL('/home') }}" class="brand-link">

        <span class="brand-text font-weight-light">PRIMEHOMES PORTAL</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>



        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ URL('/home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>

                </li>



                <li class="nav-header">General</li>

                @canany('complain-list', 'complain-create')
                    <li
                        class="nav-item {{ Request::segment(1) === 'complains' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Owners') ? 'menu-open' : null }}">
                        @can('complain-list')
                            <a href="#"
                                class="nav-link {{ Request::segment(1) === 'complains' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Owner') ? 'active' : null }}">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Complains
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        @endcan
                        <ul class="nav nav-treeview">
                            @can('complain-create')
                                <li class="nav-item">
                                    <a href="{{ route('complains.create') }}"
                                        class="nav-link {{ Request::segment(1) === 'complains' && Request::segment(2) === 'create' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('complain-list')
                                <li class="nav-item">
                                    <a href="{{ route('complains.index') }}"
                                        class="nav-link {{ Request::segment(1) === 'complains' && Request::segment(2) === 'show' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Show</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @can('owner-list')
                    <li
                        class="nav-item {{ Request::segment(1) === 'owners' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Owners') ? 'menu-open' : null }}">
                        @can('owner-list')
                            <a href="#"
                                class="nav-link {{ Request::segment(1) === 'owners' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Owner') ? 'active' : null }}">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Owners
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        @endcan
                        <ul class="nav nav-treeview">
                            @can('owner-create')
                                <li class="nav-item">
                                    <a href="{{ route('owners.create') }}"
                                        class="nav-link {{ Request::segment(1) === 'owners' && Request::segment(2) === 'create' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('owner-list')
                                <li class="nav-item">
                                    <a href="{{ route('owners.index') }}"
                                        class="nav-link {{ Request::segment(1) === 'owners' && Request::segment(2) === 'show' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Show</p>
                                    </a>
                                </li>
                            @endcan
                            @can('owner-list')
                                <li class="nav-item">
                                    <a href="{{ URL('activity_log/Owners') }}"
                                        class="nav-link {{ Request::segment(1) === 'activity_log' && Request::segment(2) === 'Owners' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Activity Log</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('tenant-list')
                    <li
                        class="nav-item {{ Request::segment(1) === 'tenants' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Tenants') ? 'menu-open' : null }}">
                        <a href="#"
                            class="nav-link {{ Request::segment(1) === 'tenants' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Tenants') ? 'active' : null }}">
                            <i class="nav-icon fas fa-door-open"></i>

                            <p>
                                Tenants
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('tenant-create')
                                <li class="nav-item">
                                    <a href="{{ route('tenants.create') }}"
                                        class="nav-link {{ request()->is('tenants.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('tenant-list')
                                <li class="nav-item">
                                    <a href="{{ route('tenants.index') }}"
                                        class="nav-link {{ request()->is('tenants.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Show</p>
                                    </a>
                                </li>
                            @endcan
                            @can('tenant-list')
                                <li class="nav-item">
                                    <a href="{{ URL('activity_log/Tenants') }}"
                                        class="nav-link {{ Request::segment(1) === 'activity_log' && Request::segment(2) === 'Tenants' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Activity Log</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @canany('unit-create', 'unit-list')

                    <li
                        class="nav-item {{ Request::segment(1) === 'units' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Units') ? 'menu-open' : null }}">
                        <a href="#"
                            class="nav-link {{ Request::segment(1) === 'units' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Units') ? 'active' : null }}">
                            <i class="nav-icon fas fa-grip-horizontal"></i>

                            <p>
                                Units
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('unit-create')
                                <li class="nav-item">
                                    <a href="{{ route('units.create') }}"
                                        class="nav-link {{ Request::segment(1) === 'units' && Request::segment(2) === 'create' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('unit-list')
                                <li class="nav-item">
                                    <a href="{{ route('units.index') }}"
                                        class="nav-link {{ request()->is('units') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Show</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ URL('activity_log/Units') }}"
                                        class="nav-link {{ Request::segment(1) === 'activity_log' && Request::segment(2) === 'Units' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Activity Log</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>

                    </li>
                @endcanany

                @canany('billing-list', 'billing-create', 'lease-list')
                    <li class="nav-header">Lease & Billings</li>
                @endcanany

                @canany('lease-list', 'lease-create')
                    <li class="nav-item {{ Request::segment(1) === 'leases' ? 'menu-open' : null }}">
                        <a href="#" class="nav-link {{ Request::segment(1) === 'leases' ? 'active' : null }}">
                            <i class="nav-icon fas fa-award"></i>

                            <p>
                                Lease Profiling
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            @can('lease-create')
                                <li class="nav-item">
                                    <a href="{{ route('leases.create') }}"
                                        class="nav-link {{ Request::segment(1) === 'leases' && Request::segment(2) === 'create' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('lease-list')
                                <li class="nav-item">
                                    <a href="{{ route('leases.index') }}"
                                        class="nav-link {{ Request::segment(1) === 'leases' && Request::segment(2) === 'index' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Show</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

                @canany('billing-list', 'billing-create')

                    <li
                        class="nav-item {{ request()->is('billings*') || request()->is('billing/reports') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('billings*') || request()->is('billing/reports') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-sync-alt fa-spin"></i>
                            <p>
                                Billings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('billing-create')
                                <li class="nav-item">
                                    <a href="{{ route('billings.create') }}"
                                        class="nav-link {{ request()->is('billings/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create Billing</p>
                                    </a>
                                </li>
                            @endcan
                            <!-- <li class="nav-item">
                                                                                <a href="" class="nav-link }">
                                                                                  <i class="far fa-circle nav-icon"></i>
                                                                                  <p>Water Readings</p>
                                                                                </a>
                                                                              </li>
                                                                              <li class="nav-item">
                                                                                <a href="" class="nav-link }">
                                                                                  <i class="far fa-circle nav-icon"></i>
                                                                                  <p>Voilation Charges</p>
                                                                                </a>
                                                                              </li> -->
                            @can('billing-list')
                                <li class="nav-item">
                                    <a href="{{ route('billings.index') }}"
                                        class="nav-link {{ request()->is('billings') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Invoices</p>
                                    </a>
                                </li>
                            @endcan

                            @can('billing-list')
                                <li class="nav-item">
                                    <a href="{{ url('/billing/reports') }}"
                                        class="nav-link {{ request()->is('billing/reports') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Invoices Report</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

                {{-- Reports --}}

                @canany('project-list', 'amenities-list', 'user-list', 'role-list')
                    <li class="nav-header">Reports</li>
                @endcanany

                <li
                    class="nav-item {{ request()->is('reports*') || request()->is('reports/complain') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('reports*') || request()->is('reports/complain') ? 'active' : '' }}">
                        <i class="fas fa-chart-line nav-icon"></i>
                        <p>
                            Reports
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('billing-create')
                            <li class="nav-item">
                                <a href="{{ url('/reports/complain') }}"
                                    class="nav-link {{ request()->is('reports/complain') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Complain Reports</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('/reports/billing') }}"
                                    class="nav-link {{ request()->is('reports/billing') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Billing Reports</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('/reports/user') }}"
                                    class="nav-link {{ request()->is('reports/user') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>User Reports</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('/reports/building') }}"
                                    class="nav-link {{ request()->is('reports/building') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Building Reports</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('/reports/lease') }}"
                                    class="nav-link {{ request()->is('reports/lease') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Lease Reports</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                {{-- End reports --}}
                @canany('project-list', 'amenities-list', 'user-list', 'role-list')
                    <li class="nav-header">Settings</li>
                @endcanany

                @canany('project-list', 'project-create')
                    <li
                        class="nav-item {{ Request::segment(1) === 'projects' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Buildings') ? 'menu-open' : null }}">
                        <a href="#"
                            class="nav-link {{ Request::segment(1) === 'projects' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Buildings') ? 'active' : null }}">

                            <i class="nav-icon far fa-building"></i>
                            <p>
                                Manage Buildings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('project-create')
                                <li class="nav-item">
                                    <a href="{{ route('projects.create') }}"
                                        class="nav-link {{ Request::segment(1) === 'projects' && Request::segment(2) === 'create' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('project-list')
                                <li class="nav-item">
                                    <a href="{{ route('projects.index') }}"
                                        class="nav-link {{ request()->is('projects') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Show</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL('activity_log/Buildings') }}"
                                        class="nav-link {{ Request::segment(1) === 'activity_log' && Request::segment(2) === 'Owner' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Activity Log</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

                @can('amenities-list')
                    <li class="nav-item {{ Request::segment(1) === 'amenities' ? 'menu-open' : null }}">
                        <a href="#" class="nav-link {{ Request::segment(1) === 'amenities' ? 'active' : null }}">

                            <i class="nav-icon fas fa-bath"></i>
                            <p>
                                Manage Amenities
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('amenitie-create')
                                <li class="nav-item">
                                    <a href="{{ route('amenities.create') }}"
                                        class="nav-link {{ Request::segment(1) === 'amenities' && Request::segment(2) === 'create' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('amenitie-list')
                                <li class="nav-item">
                                    <a href="{{ route('amenities.index') }}"
                                        class="nav-link {{ request()->is('amenities') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Show</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('user-list')

                    <li
                        class="nav-item {{ Request::segment(1) === 'users' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Users') ? 'menu-open' : null }}">
                        <a href="#"
                            class="nav-link {{ Request::segment(1) === 'users' || (Request::segment(1) === 'activity_log' && Request::segment(2) === 'Users') ? 'active' : null }}">

                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Manage Users
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('role-create')
                                <li class="nav-item">
                                    <a href="{{ route('users.create') }}"
                                        class="nav-link {{ Request::segment(1) === 'users' && Request::segment(2) === 'create' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('user-list')
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ request()->is('users') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Show</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL('activity_log/Users') }}"
                                        class="nav-link {{ Request::segment(1) === 'activity_log' && Request::segment(2) === 'Users' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Activity Log</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                @canany('role-list', 'role-create')

                    <li class="nav-item {{ Request::segment(1) === 'roles' ? 'menu-open' : null }}">
                        <a href="#" class="nav-link {{ Request::segment(1) === 'roles' ? 'active' : null }}">

                            <i class="nav-icon fas fa-user-secret"></i>
                            <p>
                                Manage roles
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('role-create')
                                <li class="nav-item">
                                    <a href="{{ route('roles.create') }}"
                                        class="nav-link {{ Request::segment(1) === 'roles' && Request::segment(2) === 'create' ? 'active' : null }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                            @endcan
                            @can('role-list')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link {{ request()->is('roles') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Show</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany('role-list', 'role-create')
                    <li class="nav-item {{ Request::segment(1) === 'roles' ? 'menu-open' : null }}">
                        <a href="#" class="nav-link {{ Request::segment(1) === 'roles' ? 'active' : null }}">

                            <i class="nav-icon fas fa-user-secret"></i>
                            <p>
                                Maintenance Service
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('MaintenanceService.create') }}"
                                    class="nav-link {{ Request::segment(1) === 'MaintenanceService' && Request::segment(2) === 'create' ? 'active' : null }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Create</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('maintenanceMail') }}"
                                    class="nav-link {{ request()->is('maintenanceMail') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mail Template</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
