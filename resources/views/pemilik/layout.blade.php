<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('pemilik/pemilik/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
  <title>SI-Machan Pemilik</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('pemilik/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('pemilik/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


  <!-- CSS -->
  <link id="pagestyle" href="{{ asset('pemilik/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />

  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #e8f5e9);
      font-family: 'Poppins', sans-serif !important;
    }
    .card-body {
        font-family: 'Poppins', sans-serif !important;

}
    .text-justify-cell {
        text-align: justify;
    }
    .main-content {
      padding-top: 50px;
      padding-left: 20px;
      padding-right: 20px;
      padding-bottom: 30px;
      transition: margin-left 0.3s ease;
    }

    @media (min-width: 1200px) {
      .main-content {
        margin-left: 220px;
      }
    }

    @media (max-width: 1199.98px) {
      #sidenav-main {
        transform: translateX(-250px);
        position: fixed;
        z-index: 1031;
      }

      body.g-sidenav-show #sidenav-main {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }
    }

    .sidenav-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1030;
    }

    .nav-link.active {
      background-color: #e8f5e9;
      font-weight: bold;
      border-radius: 10px;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-custom-green position-absolute w-100" style="z-index: -1;"></div>

  <!-- SIDEBAR -->
  <aside id="sidenav-main" class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4">
    <div class="sidenav-header text-center">
      <i class="fas fa-bars p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-xl-none" id="iconNavbarSidenav"></i>
      <div class="text-center my-3">
        <img src="{{ asset('images/logo.png') }}" alt="main_logo" width="120">
      </div>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
  <li class="nav-item">
    <a class="nav-link {{ Request::is('beranda-pemilik') ? 'active' : '' }}" href="{{ url('beranda-pemilik') }}">
      <div class="icon icon-shape icon-sm text-center me-2 mb-2">
        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
      </div>
      <span class="nav-link-text ms-1">Beranda</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ Request::is('pemilik/data_ikan*') ? 'active' : '' }}" href="{{ route('pemilik.data_ikan') }}">
      <div class="icon icon-shape icon-sm text-center me-2 mb-3">
        <i class="fas fa-fish text-primary text-sm opacity-10"></i>
      </div>
      <span class="nav-link-text ms-1">Data Ikan</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ Request::is('pemilik/data_supplier*') ? 'active' : '' }}" href="{{ route('pemilik.data_supplier') }}">
      <div class="icon icon-shape icon-sm text-center me-2 mb-2">
        <i class="ni ni-basket text-warning text-sm opacity-10"></i>
      </div>
      <span class="nav-link-text ms-1">Data Supplier</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ Request::is('pemilik/data_pasar*') ? 'active' : '' }}" href="{{ route('pemilik.data_pasar') }}">
      <div class="icon icon-shape icon-sm text-center me-2 mb-2">
        <i class="ni ni-shop text-danger text-sm opacity-10"></i>
      </div>
      <span class="nav-link-text ms-1">Data Pasar</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ Request::is('pemilik/data_konsumen*') ? 'active' : '' }}" href="{{ route('pemilik.data_konsumen') }}">
      <div class="icon icon-shape icon-sm text-center me-2 mb-2">
        <i class="ni ni-single-02 text-success text-sm opacity-10"></i>
      </div>
      <span class="nav-link-text ms-1">Data Konsumen</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ Request::is('pemilik/pembelian*') ? 'active' : '' }}" href="{{ route('pemilik.pembelian') }}">
      <div class="icon icon-shape icon-sm text-center me-2 mb-2">
        <i class="ni ni-cart text-primary text-sm opacity-10"></i>
      </div>
      <span class="nav-link-text ms-1">Data Pembelian</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ Request::is('pemilik/penjualan*') ? 'active' : '' }}" href="{{ route('pemilik.penjualan') }}" href="">
      <div class="icon icon-shape icon-sm text-center me-2 mb-2">
        <i class="ni ni-send text-info text-sm opacity-10"></i>
      </div>
      <span class="nav-link-text ms-1">Data Penjualan</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ Request::is('pemilik/laporan*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#laporanMenu" role="button" aria-expanded="false" aria-controls="laporanMenu">
      <div class="icon icon-shape icon-sm text-center me-2 mb-2">
        <i class="ni ni-collection text-warning text-sm opacity-10"></i>
      </div>
      <span class="nav-link-text ms-1">Laporan</span>
    </a>
    <div class="collapse" id="laporanMenu">
      <ul class="nav flex-column ms-4">
        <li class="nav-item">
          <a class="nav-link {{ Request::is('laporan/pembelian') ? 'active' : '' }}" href="{{ route('pemilik.lap_pembelian') }}">
            <i class="fas fa-file-alt text-sm me-2"></i> Pembelian
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('laporan/stok') ? 'active' : '' }}" href="{{ route('pemilik.lap_stok') }}">
            <i class="fas fa-file-invoice-dollar text-sm me-2"></i> Stok
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('laporan/penjualan') ? 'active' : '' }}" href="{{ route('pemilik.lap_penjualan') }}">
            <i class="fas fa-file-invoice-dollar text-sm me-2"></i> Penjualan
          </a>
        </li>
      </ul>
    </div>
  </li>

  <li class="nav-item mt-3">
    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account</h6>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <div class="icon icon-shape icon-sm text-center me-2">
        <i class="ni ni-user-run text-danger text-sm opacity-10"></i>
      </div>
      <span class="nav-link-text ms-1">Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
  </li>
</ul>

    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid">
      @yield('content')
    </div>
  </main>

  <footer class="bg-white text-center py-3">
    <div class="container">
      <span class="text-muted d-block d-sm-inline-block">&copy; 2025 by Diana Kholifah & Sinsin Kuraesin.</span>
      <span class="text-muted d-block d-sm-inline-block">Proyek Sistem Informasi</span>
    </div>
  </footer>

  <!-- JS -->
  <script src="{{ asset('pemilik/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('pemilik/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('pemilik/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('pemilik/js/plugins/smooth-scrollbar.min.js') }}"></script>
  @stack('scripts')

  <script>
    const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');

    if (iconNavbarSidenav) {
      iconNavbarSidenav.addEventListener('click', function () {
        document.body.classList.toggle('g-sidenav-show');

        if (document.body.classList.contains('g-sidenav-show')) {
          const overlay = document.createElement('div');
          overlay.classList.add('sidenav-overlay');
          document.body.appendChild(overlay);
          overlay.addEventListener('click', () => {
            document.body.classList.remove('g-sidenav-show');
            overlay.remove();
          });
        } else {
          const existingOverlay = document.querySelector('.sidenav-overlay');
          if (existingOverlay) existingOverlay.remove();
        }
      });
    }
  </script>
</body>
</html>
