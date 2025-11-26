@extends('layouts.v_template')

@section('content')

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('danger'))
        <div class="alert alert-danger">{{ session('danger') }}</div>
    @endif
    
    <div class="card shadow mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Konfirmasi Pengajuan Peminjaman Ruangan (Menunggu: {{ $ruangan_pengajuan->count() }})</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Ruangan</th>
                        <th>Peminjam</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen</th>
                        <th>Waktu Pinjam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ruangan_pengajuan as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->nama_ruangan }}</td>
                        <td>{{ $p->nama_peminjam }}</td>
                        <td>{{ $p->mata_kuliah }}</td>
                        <td>{{ $p->dosen_pengampu }}</td>
                        <td>{{ $p->jam_pinjam }} s/d {{ $p->jam_kembali }}</td>
                        <td><span class="badge bg-warning text-dark">{{ $p->status }}</span></td>
                        <td>
                            <form action="{{ route('admin.pengajuan.ruangan.setujui', $p->peminjaman_ruangan_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>

                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolakRuangan{{ $p->peminjaman_ruangan_id }}">
                                Tolak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada pengajuan ruangan yang menunggu konfirmasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Konfirmasi Pengajuan Peminjaman Fasilitas (Menunggu: {{ $fasilitas_pengajuan->count() }})</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Fasilitas yang Dipinjam</th>
                        <th>Waktu Pinjam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($fasilitas_pengajuan as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->nama_peminjam }}</td>
                        <td>
                            @if($p->detail->count() > 0)
                                <ul>
                                    @foreach($p->detail as $item)
                                        <li>{{ $item->nama_fasilitas }} ({{ $item->jumlah }})</li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $p->jam_pinjam }} s/d {{ $p->jam_kembali }}</td>
                        <td><span class="badge bg-warning text-dark">{{ $p->status }}</span></td>
                        <td>
                            <form action="{{ route('admin.pengajuan.fasilitas.setujui', $p->peminjaman_fasilitas_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>

                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolakFasilitas{{ $p->peminjaman_fasilitas_id }}">
                                Tolak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada pengajuan fasilitas yang menunggu konfirmasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    @foreach($ruangan_pengajuan as $p)
    <div class="modal fade" id="modalTolakRuangan{{ $p->peminjaman_ruangan_id }}">
        <div class="modal-dialog">
            <form action="{{ route('admin.pengajuan.ruangan.tolak', $p->peminjaman_ruangan_id) }}" method="POST">
                @csrf
                <div class="modal-content text-dark">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Konfirmasi Penolakan Ruangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menolak pengajuan ruangan <b>{{ $p->nama_ruangan }}</b> oleh **{{ $p->nama_peminjam }}**?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Sekarang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach


    @foreach($fasilitas_pengajuan as $p)
    <div class="modal fade" id="modalTolakFasilitas{{ $p->peminjaman_fasilitas_id }}">
        <div class="modal-dialog">
            <form action="{{ route('admin.pengajuan.fasilitas.tolak', $p->peminjaman_fasilitas_id) }}" method="POST">
                @csrf
                <div class="modal-content text-dark">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Konfirmasi Penolakan Fasilitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menolak pengajuan fasilitas oleh **{{ $p->nama_peminjam }}**?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Sekarang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach

</div>

@endsection