<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">

                {{-- === Core Section === --}}
                <div class="sb-sidenav-menu-heading">Core</div>

                {{-- Dashboard dinamis berdasarkan role --}}
                @php
                    // aman: pakai auth() kalau user login, fallback ke null
                    $role = auth()->check() ? auth()->user()->role : null;
                    $username = auth()->check() ? (auth()->user()->username ?? auth()->user()->name ?? 'User') : 'Guest';

                    switch ($role) {
                        case 'Admin': $dashboardLink = '/admin/dashboard'; break;
                        case 'Mahasiswa': $dashboardLink = '/mahasiswa/dashboard'; break;
                        case 'Dosen': $dashboardLink = '/dosen/dashboard'; break;
                        case 'Teknisi': $dashboardLink = '/teknisi/dashboard'; break;
                        default: $dashboardLink = '/'; break;
                    }
                @endphp


                <a class="nav-link" href="{{ $dashboardLink }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-home-alt"></i></div>
                    Dashboard
                </a>
                {{-- === Tambahan umum === --}}
                <a class="nav-link" href="/profile">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-alt"></i></div>
                    Profile
                </a>
                {{-- === Menu dinamis sesuai role === --}}
                <div class="sb-sidenav-menu-heading">Menu</div>

                {{-- ADMIN --}}
                @if($role === 'Admin')

                    <!-- FASILITAS -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFasilitas"
                        aria-expanded="false" aria-controls="collapseFasilitas">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Fasilitas
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseFasilitas" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="/admin/pengajuan">Daftar peminjaman</a>
                            <a class="nav-link" href="{{ route('admin.komplain.index') }}">Laporan Kerusakan Fasilitas</a>
                            <a class="nav-link" href="{{ route('admin.komplain.riwayat') }}">riwayat kerusakan</a>
                        </nav>
                    </div>


                    <!-- INVENTARIS ASET TETAP -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAset"
                        aria-expanded="false" aria-controls="collapseAset">
                        <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                        Inventaris Aset Tetap
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseAset" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <!-- {{ route('aset.index') }} -->
                            <a class="nav-link" href="/admin/aset">Kelola Aset</a>
                            <a class="nav-link" href="/admin/aset/laporan">Laporan Aset</a>
                            <a class="nav-link" href="{{ route('notifikasi.aset') }}">Notifikasi Aset</a>


                        </nav>
                    </div>


                    <!-- MANAJEMEN UMUM -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManajemen"
                        aria-expanded="false" aria-controls="collapseManajemen">
                        <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                        Manajemen Umum
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseManajemen" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="/admin/manajemen/kelola">Kelola Pengguna</a>
                            <!-- <a class="nav-link" href="/admin/admin/riwayat">Riwayat Aktivitas Admin</a>
                            <a class="nav-link" href="/admin/profil">Profil Admin</a> -->
                        </nav>
                    </div>

                @endif


                {{-- TEKNISI --}}
                @if($role === 'Teknisi')
                    <!-- <a class="nav-link" href="/teknisi/tugas">
                        <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i> </div>
                        Detail Tugas Perbaikan
                    </a>
                    <a class="nav-link" href="/teknisi/riwayat">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-invoice"></i>
                        </div>
                        Riwayat Perbaikan
                    </a>
                    <a class="nav-link" href="/teknisi/notifikasi">
                        <div class="sb-nav-link-icon"><i class="fas fa-bell"></i>
                        </div>
                        Notifikasi Teknisi
                    </a>
                     -->
                    <a class="nav-link" href="/teknisi/maintenance">
                        <div class="sb-nav-link-icon"><i class="fas fa-tools"></i>
                        </div>
                        Maintenance
                    </a>
                    <a class="nav-link" href="{{ route('teknisi.komplain.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tools"></i>
                        </div>
                        Perbaikan Komplain
                    </a>


                @endif

                {{-- MAHASISWA --}}
                @if($role === 'Mahasiswa')
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManajemen"
                        aria-expanded="false" aria-controls="collapseManajemen">
                        <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                        Peminjaman
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseManajemen" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="/mahasiswa/peminjaman/ruangan">Peminjaman Ruangan</a>
                            <a class="nav-link" href="/mahasiswa/peminjaman/fasilitas">Peminjaman Fasilitas</a>
                            <a class="nav-link" href="/mahasiswa/peminjaman/komplain">Komplain</a>
                        </nav>
                    </div>
                @endif

                {{-- DOSEN --}}
                @if($role === 'Dosen')
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManajemen"
                        aria-expanded="false" aria-controls="collapseManajemen">
                        <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                        Peminjaman
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseManajemen" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="/dosen/peminjaman/ruangan">Peminjaman Ruangan</a>
                            <a class="nav-link" href="/dosen/peminjaman/fasilitas">Peminjaman Fasilitas</a>
                            <a class="nav-link" href="/dosen/peminjaman/komplain">Komplain</a>
                        </nav>
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ $username ?? 'Guest' }} ({{ $role ?? 'Unknown' }})
        </div>
    </nav>
</div>
