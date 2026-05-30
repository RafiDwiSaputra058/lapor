<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #0a1a3a; position: sticky; top: 0; height: 100vh; overflow-y: auto; overflow-x: hidden; z-index: 1020;">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-text mx-3">Lapor DPM</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Master Data
    </div>

    <li class="nav-item {{ request()->routeIs('admin.resident.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.resident.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Data Warga</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.report-category.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.report-category.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Data Kategori</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.report.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Data Laporan</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>