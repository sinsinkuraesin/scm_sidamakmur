<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('pemilik/pemilik/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
  <title>
    @yield('title', 'PD.Sidamakmur - Pemilik')
  </title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('pemilik/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('pemilik/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('pemilik/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />

  <style>
    /* Hilangkan scroll secara global */
    /* Sembunyikan scrollbar tapi masih bisa scroll (optional) */
#sidenav-main::-webkit-scrollbar {
  display: none;
}
#sidenav-main {
  -ms-overflow-style: none;  /* IE/Edge */
  scrollbar-width: none;     /* Firefox */
}

    .main-content {
      margin-left: 220px;
    }

    .navbar-nav .nav-link {
      display: flex;
      align-items: center;
    }

    .navbar-nav .nav-link .icon {
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .bg-custom-green {
  background: linear-gradient(90deg, #4CAF50, #2E7D32); /* Hijau sesuai logo */
}
.text-custom-green {
  color: #2E7D32 !important;
}


  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-custom-green position-absolute w-100"></div>

  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
  <div class="sidenav-header text-center">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <div class="text-center my-3">
  <img src="{{ asset('images/logo.png') }}" alt="main_logo" width="120">
</div>
  </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="{{ url('beranda-admin') }}">
            <div class="icon icon-shape icon-sm text-center me-2">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('ikan.index') }}">
            <div class="icon icon-shape icon-sm text-center me-2">
              <i class="fas fa-fish text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Ikan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('supplier.index') }}">
            <div class="icon icon-shape icon-sm text-center me-2">
              <i class="ni ni-basket text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Supplier</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('pasar.index') }}">
            <div class="icon icon-shape icon-sm text-center me-2">
              <i class="ni ni-shop text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Pasar</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('konsumen.index') }}">
            <div class="icon icon-shape icon-sm text-center me-2">
              <i class="ni ni-single-02 text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Konsumen</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('beli.index') }}">
            <div class="icon icon-shape icon-sm text-center me-2">
              <i class="ni ni-cart text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Pembelian</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('jual.index') }}">
            <div class="icon icon-shape icon-sm text-center me-2">
              <i class="ni ni-send text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Penjualan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#laporanMenu" role="button" aria-expanded="false" aria-controls="laporanMenu">
            <div class="icon icon-shape icon-sm text-center me-2">
              <i class="ni ni-collection text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Laporan</span>
          </a>
          <div class="collapse" id="laporanMenu">
            <ul class="nav flex-column ms-4">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('laporan.pembelian') }}">
                  <i class="fas fa-file-alt text-sm me-2"></i> Pembelian
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('laporan.penjualan') }}">
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

  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    @yield('content')
  </main>

  <footer class="bg-white text-center py-3">
  <div class="container">
    <span class="text-muted d-block d-sm-inline-block">
      &copy; 2025 by Diana Kholifah & Sinsin Kuraesin.
    </span>
    <span class="text-muted d-block d-sm-inline-block float-sm-right mt-1 mt-sm-0">
      Proyek Sistem Informasi <i class="ti-heart text-danger ml-1"></i>
    </span>
  </div>
</footer>

  <!-- Core JS Files -->
  <script src="{{ asset('pemilik/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('pemilik/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('pemilik/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('pemilik/js/plugins/smooth-scrollbar.min.js') }}"></script>
  @stack('scripts')

  <script>
    const sidenavMain = document.getElementById('sidenav-main');
    const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');

    if (iconNavbarSidenav) {
      iconNavbarSidenav.addEventListener('click', () => {
        if (sidenavMain.classList.contains('d-none')) {
          sidenavMain.classList.remove('d-none');
          sidenavMain.style.transform = 'translateX(0)';
        } else {
          sidenavMain.style.transform = 'translateX(-100%)';
          setTimeout(() => sidenavMain.classList.add('d-none'), 300);
        }
      });
    }
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
