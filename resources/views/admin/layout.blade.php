<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sidamakmur Admin</title>

  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('js/select.dataTables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('images/logos.png') }}" />

  <style>
    /* Hapus highlight biru dari parent menu dropdown */
    .nav-dropdown.menu-open > .nav-link {
      background: none !important;
      color: inherit !important;
    }
  </style>
</head>

<body>
<div class="container-scroller">
  <!-- Navbar -->
  <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
      <a class="navbar-brand brand-logo mr-5" href="#"><img src="{{ asset('images/logo.png') }}" class="mr-2" alt="logo" style="height: 60px;" /></a>
      <a class="navbar-brand brand-logo-mini" href="#"><img src="{{ asset('images/logos.png') }}" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="icon-menu"></span>
      </button>
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item nav-profile dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
            <img src="{{ asset('images/faces/face28.jpg') }}" alt="profile"/>
          </a>
        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="icon-menu"></span>
      </button>
    </div>
  </nav>

  <div class="container-fluid page-body-wrapper">
    <!-- Sidebar -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      @php
        $laporanAktif = Request::is('laporan/pembelian') || Request::is('laporan/penjualan');
      @endphp
      <ul class="nav">
        <li class="nav-item"><a class="nav-link" href="{{ url('beranda-admin') }}"><i class="icon-grid menu-icon"></i><span class="menu-title">Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('ikan.index') }}"><i class="icon-layout menu-icon"></i><span class="menu-title">Data Ikan</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('supplier.index') }}"><i class="icon-columns menu-icon"></i><span class="menu-title">Data Supplier</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('pasar.index') }}"><i class="icon-bar-graph menu-icon"></i><span class="menu-title">Data Pasar</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('konsumen.index') }}"><i class="ti-user menu-icon"></i><span class="menu-title">Data Konsumen</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('beli.index') }}"><i class="icon-grid-2 menu-icon"></i><span class="menu-title">Data Pembelian</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('jual.index') }}"><i class="icon-contract menu-icon"></i><span class="menu-title">Data Penjualan</span></a></li>

        <li class="nav-item {{ Request::is('laporan/pembelian') || Request::is('laporan/penjualan') ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#laporanTransaksi"
                aria-expanded="{{ Request::is('laporan/pembelian') || Request::is('laporan/penjualan') ? 'true' : 'false' }}"
                aria-controls="laporanTransaksi">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Laporan</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('laporan/pembelian') || Request::is('laporan/penjualan') ? 'show' : '' }}" id="laporanTransaksi">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('laporan/pembelian') ? 'active' : '' }}" href="{{ route('laporan.pembelian') }}">
                    Laporan Pembelian
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('laporan/penjualan') ? 'active' : '' }}" href="{{ route('laporan.penjualan') }}">
                    Laporan Penjualan
                    </a>
                </li>
                </ul>
            </div>
            </li>


        <li class="nav-item">
          <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="icon-power menu-icon"></i>
            <span class="menu-title">Logout</span>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>
      </ul>
    </nav>

    <!-- Main Panel -->
    <div class="main-panel">
      @yield('content')
      <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2025 by Diana Kholifah & Sinsin Kuraesin.</span>
          <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Proyek Sistem Informasi <i class="ti-heart text-danger ml-1"></i></span>
        </div>
      </footer>
    </div>
  </div>
</div>

<!-- JS -->
<script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('vendors/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('js/dataTables.select.min.js') }}"></script>

<!-- Template JS -->
<script src="{{ asset('js/off-canvas.js') }}"></script>
<script src="{{ asset('js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('js/template.js') }}"></script>
<script src="{{ asset('js/settings.js') }}"></script>
<script src="{{ asset('js/todolist.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="{{ asset('js/Chart.roundedBarCharts.js') }}"></script>

<script>
  $(document).ready(function () {
    // Auto open collapse if on laporan page
    var url = window.location.href;
    if (url.includes("laporan/pembelian") || url.includes("laporan/penjualan")) {
      $('#laporanTransaksi').collapse('show');
    }
  });
</script>

</body>
</html>
