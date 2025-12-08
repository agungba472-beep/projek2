@extends('layouts.v_template')

@section('content')

{{-- ===================================================== --}}
{{-- LOG PEMINJAMAN RUANGAN (Menggunakan $data) --}}
{{-- ===================================================== --}}
<div class="container mt-4">
    <div class="card shadow">

        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h4>Log Riwayat Peminjaman Ruangan</h4>
            <a href="{{ route('admin.pengajuan.ruangan.index') }}" class="btn btn-warning btn-sm">Refresh Data</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Status</th>
                        <th>Ruangan</th>
                        <th>Peminjam</th>
                        <th>Jam Pinjam</th>
                        <th>Jam Kembali</th>
                        <th>Bukti Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if(strtolower($r->status) == 'dipinjam')
                                <span class="badge bg-success">DIPINJAM</span>
                            @elseif(strtolower($r->status) == 'dikembalikan')
                                <span class="badge bg-info text-dark">DIKEMBALIKAN</span>
                            @else
                                <span class="badge bg-secondary">STATUS LAIN</span>
                            @endif
                        </td>
                        <td>{{ $r->nama_ruangan }}</td>
                        <td>{{ $r->nama_peminjam }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->jam_pinjam)->format('d/m H:i') }}</td>
                        <td>{{ $r->jam_kembali ? \Carbon\Carbon::parse($r->jam_kembali)->format('d/m H:i') : '-' }}</td>

                        <td>
                            @if(isset($r->bukti_foto) && $r->bukti_foto) {{-- Ditambahkan isset() untuk keamanan --}}
                                <button class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBuktiRuangan{{ $r->peminjaman_ruangan_id }}">
                                    Lihat Bukti
                                </button>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <td>
                            <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalForceDeleteRuangan{{ $r->peminjaman_ruangan_id }}">
                                Hapus Log
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada riwayat peminjaman ruangan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===================================================== --}}
{{-- LOG RIWAYAT PEMINJAMAN FASILITAS (Menggunakan $fasilitas_log) --}}
{{-- ===================================================== --}}
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h4>Log Riwayat Peminjaman Fasilitas</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped" id="dataTableFasilitasLog">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Status</th>
                        <th>Peminjam</th>
                        <th>Fasilitas</th>
                        <th>Waktu Pinjam</th>
                        <th>Waktu Kembali</th>
                        <th>Bukti Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($fasilitas_log as $fl)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if(strtolower($fl->status) == 'dipinjam') {{-- TAMBAHKAN BARIS INI --}}
                                <span class="badge bg-success">DIPINJAM</span>
                            @elseif(strtolower($fl->status) == 'disetujui')
                                <span class="badge bg-success">DISETUJUI</span>
                            @elseif(strtolower($fl->status) == 'menunggu')
                                <span class="badge bg-warning text-dark">MENUNGGU</span>
                            @elseif(strtolower($fl->status) == 'dikembalikan')
                                <span class="badge bg-info text-dark">DIKEMBALIKAN</span>
                            @else
                                <span class="badge bg-secondary">{{ strtoupper($fl->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $fl->nama_peminjam }}</td>
                        <td>
                            @if(isset($fl->detail) && $fl->detail->count() > 0) {{-- Pengecekan Relasi --}}
                                <ul class="mb-0">
                                    @foreach($fl->detail as $d)
                                        <li>{{ $d->nama_fasilitas }} ({{ $d->jumlah }})</li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($fl->jam_pinjam)->format('d/m H:i') }}</td>
                        <td>{{ $fl->jam_kembali ? \Carbon\Carbon::parse($fl->jam_kembali)->format('d/m H:i') : '-' }}</td>

                        <td>
                            @if(isset($fl->bukti_foto) && $fl->bukti_foto)
                                <button class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBuktiFasilitas{{ $fl->peminjaman_fasilitas_id }}">
                                    Lihat Bukti
                                </button>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <td>
                            <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalForceDeleteFasilitas{{ $fl->peminjaman_fasilitas_id }}">
                                Hapus Log
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada riwayat peminjaman fasilitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



{{-- ===================================================== --}}
{{-- MODAL MODAL RUANGAN (Menggunakan $data) --}}
{{-- ===================================================== --}}
@if($data->count() > 0) {{-- HANYA LOOP JIKA ADA DATA --}}
    @foreach($data as $r)
    
    {{-- MODAL HAPUS LOG RUANGAN --}}
    <div class="modal fade" id="modalForceDeleteRuangan{{ $r->peminjaman_ruangan_id }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.ruangan.force.delete', $r->peminjaman_ruangan_id) }}">
                @csrf @method('DELETE')

                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5>Konfirmasi Hapus Log Ruangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-danger">
                            Anda akan menghapus permanen log peminjaman ruangan
                            <b>{{ $r->nama_ruangan }}</b> oleh <b>{{ $r->nama_peminjam }}</b>.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger">Hapus Permanen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL BUKTI RUANGAN --}}
    <div class="modal fade" id="modalBuktiRuangan{{ $r->peminjaman_ruangan_id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Bukti Pengembalian: {{ $r->nama_ruangan }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    @if(isset($r->bukti_foto) && $r->bukti_foto)
                        <img src="{{ asset('bukti_ruangan/' . $r->bukti_foto) }}" class="img-fluid">
                    @else
                        <p class="text-muted">Tidak ada foto bukti.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
    @endforeach
@endif


{{-- ===================================================== --}}
{{-- MODAL MODAL FASILITAS (Menggunakan $fasilitas_log) --}}
{{-- ===================================================== --}}
@if($fasilitas_log->count() > 0) {{-- HANYA LOOP JIKA ADA DATA --}}
    @foreach($fasilitas_log as $fl)
    {{-- MODAL HAPUS LOG FASILITAS --}}
    <div class="modal fade" id="modalForceDeleteFasilitas{{ $fl->peminjaman_fasilitas_id }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.fasilitas.force.delete', $fl->peminjaman_fasilitas_id) }}">
                @csrf @method('DELETE')

                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5>Konfirmasi Hapus Log Fasilitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-danger">
                            Anda akan menghapus permanen log peminjaman fasilitas oleh
                            <b>{{ $fl->nama_peminjam }}</b>.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger">Hapus Permanen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL BUKTI FASILITAS --}}
    <div class="modal fade" id="modalBuktiFasilitas{{ $fl->peminjaman_fasilitas_id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Bukti Pengembalian Fasilitas oleh: {{ $fl->nama_peminjam }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    @if(isset($fl->bukti_foto) && $fl->bukti_foto)
                        <img src="{{ asset('bukti_fasilitas/' . $fl->bukti_foto) }}" class="img-fluid">
                    @else
                        <p class="text-muted">Tidak ada foto bukti.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
    @endforeach
@endif

@endsection