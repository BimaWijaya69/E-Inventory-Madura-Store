@php
    $isTransaksiActive = request()->routeIs(
        'material-masuks',
        'material-keluars',
        'material-masuks.create',
        'material-masuks.edit',
        'create-material-keluars',
        'edit-material-keluars',
    );
@endphp

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('materials') ? '' : 'collapsed' }}" href="{{ route('materials') }}">
                <i class="fa-solid fa-box"></i>
                <span>Material</span>
            </a>
        </li>
        {{-- Transaksi --}}
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-collection me-2"></i>
                <span>Transaksi</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>


            <ul id="master-nav" class="nav-content collapse {{ $isTransaksiActive ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">

                <li>
                    <a href="{{ route('material-masuks') }}"
                        class="{{ request()->routeIs('material-masuks', 'material-masuks.create', 'material-masuks.edit') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck nav-icon" style="font-size: 14px;"></i>
                        <span>Penerimaan Material</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('material-keluars') }}"
                        class="{{ request()->routeIs('material-keluars', 'create-material-keluars', 'edit-material-keluars') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-ramp-box nav-icon"style="font-size: 14px;"></i>
                        <span>Pengeluaran Material</span>
                    </a>
                </li>

            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users') ? '' : 'collapsed' }}" href="{{ route('users') }}">
                <i class="fa-solid fa-user-astronaut"></i>
                <span>Manajemen User</span>
            </a>
        </li>

    </ul>
</aside>
