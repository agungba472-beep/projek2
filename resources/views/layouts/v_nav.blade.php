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
                <a class="nav-link" href="{{ route('profile.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-alt"></i></div>
                    Profile
                </a>
                {{-- === Menu dinamis sesuai role === --}}
                <div class="sb-sidenav-menu-heading">Menu</div>

                {{-- ADMIN --}}
                @if($role === 'Admin')

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFasilitas"
                        aria-expanded="false" aria-controls="collapseFasilitas">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Fasilitas
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseFasilitas" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('admin.pengajuan.index') }}">Daftar peminjaman</a>
                            <a class="nav-link" href="{{ route('admin.komplain.index') }}">Laporan Kerusakan Fasilitas</a>
                            <a class="nav-link" href="{{ route('admin.komplain.riwayat') }}">Riwayat Kerusakan</a>
                        </nav>
                    </div>


                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAset"
                        aria-expanded="false" aria-controls="collapseAset">
                        <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                        Inventaris Aset Tetap
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseAset" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('aset.index') }}">Kelola Aset</a>
                            
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLaporan"
                                aria-expanded="false" aria-controls="collapseLaporan">
                                Laporan Aset
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLaporan" data-bs-parent="#collapseAset">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('laporan.aset') }}">Lap. Ruangan & Kondisi</a>
                                    <a class="nav-link" href="{{ route('laporan.inventaris.index') }}">Lap. Inventaris Bulanan</a>
                                </nav>
                            </div>

                            <a class="nav-link" href="{{ route('notifikasi.aset') }}">Notifikasi Aset</a>
                        </nav>
                    </div>


                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManajemen"
                        aria-expanded="false" aria-controls="collapseManajemen">
                        <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                        Manajemen Umum
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseManajemen" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('ruangan.index') }}">Kelola Ruangan</a>
                            <a class="nav-link" href="{{ route('pengguna.index') }}">Kelola Pengguna</a>
                        </nav>
                    </div>

                @endif


                {{-- TEKNISI --}}
                @if($role === 'Teknisi')
                    <a class="nav-link" href="{{ route('maintenance.index') }}">
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
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePeminjamanMhs"
                        aria-expanded="false" aria-controls="collapsePeminjamanMhs">
                        <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                        Peminjaman
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapsePeminjamanMhs" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('pinjam.ruangan.index') }}">Peminjaman Ruangan</a>
                            <a class="nav-link" href="{{ route('pinjam.fasilitas.index') }}">Peminjaman Fasilitas</a>
                            <a class="nav-link" href="{{ route('komplain.index') }}">Komplain</a>
                        </nav>
                    </div>
                @endif

                {{-- DOSEN (Revisi #7 & #8) --}}
                @if($role === 'Dosen')
                    
                    <a class="nav-link" href="{{ route('dosen.aset.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                        Daftar Aset
                    </a>

                    <a class="nav-link" href="{{ route('dosen.komplain.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-wrench"></i></div>
                        Komplain
                    </a>
                    
                    {{-- CATATAN: Menu Peminjaman Dosen DIHAPUS sesuai Revisi #7 --}}

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