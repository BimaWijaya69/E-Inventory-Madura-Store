@php
    $menus = [
        (object) [
            'title' => 'Dashboard',
            'path' => 'dashboard',
            'icon' => 'fa-solid fa-house',
        ],
        (object) [
            'title' => 'Manajemen Material',
            'path' => 'products',
            'icon' => 'fa-solid fa-box',
        ],
        (object) [
            'title' => 'Manajemen User',
            'path' => 'suppliers',
            'icon' => 'fa-solid fa-user-astronaut',
        ],
        // (object) [
        //     'title' => 'Laporan Barang',
        //     'path' => 'laporans',
        //     'icon' => 'fas fa-file-pdf',
        // ],
    ];
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4 position-fixed">
    <script src="https://kit.fontawesome.com/b61a43e08f.js" crossorigin="anonymous"></script>

    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('images/logo.gif') }}" class="img-fluid d-block mx-auto"
            style="max-width: 100%; height: auto; display: block;" alt="AdminLTE Logo">
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="height: 100vh; overflow-y: auto;">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                {{-- Menu utama (tanpa dropdown) --}}
                @foreach ($menus as $menu)
                    <li class="nav-item">
                        <a href="{{ $menu->path[0] !== '/' ? '/' . $menu->path : $menu->path }}"
                            class="nav-link {{ Request::is($menu->path . '*') ? 'active' : '' }}"
                            style="{{ Request::is($menu->path . '*') ? 'background-color: #dbeb04; color: black;' : '' }}">
                            <i class="nav-icon {{ $menu->icon }}"></i>
                            <p>{{ $menu->title }}</p>
                        </a>
                    </li>
                @endforeach

                {{-- Menu dropdown: Transaksi --}}
                @php
                    $isTransaksiActive = Request::is('brgmasuks*') || Request::is('brgkeluars*');
                @endphp

                <li class="nav-item {{ $isTransaksiActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link"
                        style="background-color: transparent !important; color: #c2c7d0 !important;">
                        <i class="nav-icon fa-solid fa-money-bill-transfer"></i>
                        <p>
                            Transaksi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/brgmasuks" class="nav-link {{ Request::is('brgmasuks*') ? 'active' : '' }}"
                                style="padding-left: 35px; {{ Request::is('brgmasuks*') ? 'background-color: #dbeb04; color: black;' : '' }}">
                                <i class="fa-solid fa-truck nav-icon"></i>
                                <p>Material Masuk</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/brgkeluars" class="nav-link {{ Request::is('brgkeluars*') ? 'active' : '' }}"
                                style=" padding-left: 35px; {{ Request::is('brgkeluars*') ? 'background-color: #dbeb04; color: black;' : '' }}">
                                <i class="fa-solid fa-truck-ramp-box nav-icon"></i>
                                <p>Material Keluar</p>
                            </a>
                        </li>
                    </ul>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
