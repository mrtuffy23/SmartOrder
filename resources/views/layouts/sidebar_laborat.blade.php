<aside class="main-sidebar sidebar-dark-info elevation-4">
    <a href="#" class="brand-link bg-info">
        <img src="{{ asset('assets/dist/img/logo-indotex.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-bold">LABORAT SYSTEM</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name ?? '-' }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-header">DATA MASTER LABORAT</li>

                <li class="nav-item">
                    <a href="{{ route('dyestuffs.index') }}" class="nav-link {{ request()->routeIs('dyestuffs.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tint"></i>
                        <p>Master Zat Warna</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('chemicals.index') }}" class="nav-link {{ request()->routeIs('chemicals.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-vial"></i>
                        <p>Master Bahan Kimia</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('processes.index') }}" class="nav-link {{ request()->routeIs('processes.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-flask"></i>
                        <p>Master Proses & SOP</p>
                    </a>
                </li>

                <li class="nav-header mt-3">RESEP & PRODUKSI</li>

                <li class="nav-item">
                    <a href="{{ route('order-recipes.index') }}" class="nav-link {{ request()->routeIs('order-recipes.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Buku Resep Original</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('job-tickets.index') }}" class="nav-link {{ request()->routeIs('job-tickets.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-ticket-alt"></i>
                        <p>Job Ticket Produksi</p>
                    </a>
                </li>

                @if(auth()->user()->role != 'laborat')
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link bg-danger text-white">
                        <i class="nav-icon fas fa-arrow-left"></i>
                        <p>Kembali ke Smart Order</p>
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