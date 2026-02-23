<aside class="main-sidebar sidebar-dark-primary elevation-4">
  
  <a href="/" class="brand-link">
    <img src="{{ asset('assets/dist/img/logo-indotex.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Smart Order</span>
  </a>

  <div class="sidebar">
    
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image" onerror="this.src='https://ui-avatars.com/api/?name={{ auth()->user()->name }}'">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ auth()->user()->name }}</a>
        <span class="badge badge-info">{{ auth()->user()->role }}</span>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        @if(auth()->user()->role == 'superadmin')
        <li class="nav-header">ADMINISTRATOR</li>
        <li class="nav-item">
          <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Kelola User</p>
          </a>
        </li>
        @endif

        @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'gudang']))
        <li class="nav-header">DATA MASTER</li>
        
        <li class="nav-item">
          <a href="{{ route('buyers.index') }}" class="nav-link {{ request()->routeIs('buyers.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-tie"></i>
            <p>Data Buyers</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('fabrics.index') }}" class="nav-link {{ request()->routeIs('fabrics.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-scroll"></i>
            <p>Data Kain</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('colors.index') }}" class="nav-link {{ request()->routeIs('colors.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-palette"></i>
            <p>Data Warna</p>
          </a>
        </li>
        @endif
        
        @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
        <li class="nav-header">TRANSAKSI</li>

        <li class="nav-item">
          <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>Order</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Transaksi</p>
          </a>
        </li>
        @endif
        @if(in_array(auth()->user()->role, ['superadmin', 'gudang', 'admin']))
        <li class="nav-header">GUDANG & STOK</li>

        <li class="nav-item">
          <a href="{{ route('receipts.index') }}" class="nav-link {{ request()->routeIs('receipts.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-truck-loading"></i>
            <p>Penerimaan Kain</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('stocks.index') }}" class="nav-link {{ request()->routeIs('stocks.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Data Saldo/Stok</p>
          </a>
        </li>
        @endif
        @if(in_array(auth()->user()->role, ['superadmin', 'produksi', 'gudang']))
        <li class="nav-header">PRODUKSI & BARANG JADI</li>
        
        <li class="nav-item">
          <a href="{{ route('pemartaians.index') }}" class="nav-link {{ request()->routeIs('pemartaians.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-industry"></i>
            <p>Pemartaian (Batching)</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('wip.index') }}" class="nav-link {{ request()->routeIs('wip.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-hourglass-half"></i>
            <p>Data Kain WIP</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('quality_finishes.index') }}" class="nav-link {{ request()->routeIs('quality_finishes.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-check-double"></i>
            <p>Data Quality Finish</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('deliveries.index') }}" class="nav-link {{ request()->routeIs('deliveries.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-truck"></i>
            <p>Delivery (Surat Jalan)</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('stok_barang_jadi.index') }}" class="nav-link {{ request()->routeIs('stok_barang_jadi.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cubes"></i>
            <p>Stok Barang Jadi</p>
          </a>
        </li>
        @endif

        <li class="nav-item mt-4">
           <form action="{{ route('logout') }}" method="POST">
             @csrf
             <button type="submit" class="nav-link text-left text-white" style="background-color: #dc3545; border: none; width: 100%; border-radius: 4px; padding-left: 1rem;">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
             </button>
           </form>
        </li>

      </ul>
    </nav>
  </div>
</aside>