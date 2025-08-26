<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | @yield('title','Dashboard')</title>
  <!-- AdminLTE 3 (Bootstrap 4) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
  <style>
    /* Theme gradient (blue → purple) for sidebar */
    .main-sidebar { 
      background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%) !important; /* from-blue-600 to-purple-600 */
      color: #fff;
    }
    .main-sidebar .brand-link, .main-sidebar .brand-link .brand-text { color: #fff !important; }
    .main-sidebar .nav-sidebar .nav-item>.nav-link { color: rgba(255,255,255,.9); }
    .main-sidebar .nav-sidebar .nav-item>.nav-link.active, 
    .main-sidebar .nav-sidebar .nav-item>.nav-link:hover { 
      background: rgba(255,255,255,.15) !important; 
      color: #fff !important; 
    }
    .main-header.navbar { box-shadow: 0 1px 2px rgba(0,0,0,.06); }
    /* Sidebar icon color to white */
    .main-sidebar .nav-sidebar .nav-item > .nav-link .nav-icon { color: #fff !important; }
    /* Navbar user icon to theme color */
    .main-header .nav-link .fa-user { color: #7c3aed; }
    /* Dashboard small-box icons with gradient color */
    .small-box .icon i {
      background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      color: transparent;
      opacity: 1 !important;
    }
    .content-header h1 { font-weight: 800; }
  </style>
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block"><a href="{{ route('home') }}" class="nav-link">View site</a></li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i> {{ auth()->user()->name ?? 'Admin' }}
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <span class="dropdown-item-text">{{ auth()->user()->email ?? '' }}</span>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}" class="px-3">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-block btn-sm">Logout</button>
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
      <span class="brand-text font-weight-bold">WiseDynamic Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ request()->is('admin/slides*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('admin/slides*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Website Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.slides.index') }}" class="nav-link {{ request()->routeIs('admin.slides.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Slider</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>Orders</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('page_title','Dashboard')</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer small">
    <strong>&copy; {{ date('Y') }} WiseDynamic.</strong> All rights reserved.
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
