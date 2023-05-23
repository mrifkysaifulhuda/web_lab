<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Silab</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Inventori</li>
            <li class="{{ $type_menu === 'alat' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('alat') }}"><i class="fas fa-fire"></i> <span>Alat</span></a>
            </li>
            <li class="{{ $type_menu === 'bahan' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('bahan') }}"><i class="fas fa-th-large"></i> <span>Bahan</span></a>
            </li>

            <li class="menu-header">Pengajuan</li>
            <li class="nav-item dropdown @if($type_menu == 'mdpraktikum' or $type_menu == 'lab' ) active @endif">
                <a href="#"
                    class="nav-link has-dropdown"
                    data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Kebutuhan</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ $type_menu === 'mdpraktikum' ? 'active' : '' }}">
                        <a class="nav-link"
                            href="{{ route('mdpraktikum') }}">Praktikum</a>
                    </li>
                    <li class="{{ $type_menu === 'lab' ? 'active' : '' }}">
                        <a class="nav-link"
                            href="{{ route('lab') }}">Laboratorium</a>
                    </li>
                </ul>
            </li>
            @if(auth()->user()->role == "admin")
            <li class="menu-header">Administrasi</li>
            <li class="{{ $type_menu === 'users' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('user') }}"><i class="far fa-user"></i> <span>Pengguna</span></a>
            </li>
            @endif
        </ul>
    </aside>
</div>

