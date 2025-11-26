@extends('layouts.v_template')

@section('content')

<div class="container mt-4">
    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">
            <h4>Peminjaman Ruangan</h4>

            <!-- Tombol Tambah -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                Ajukan Peminjaman
            </button>
        </div>

        <div class="card-body">

            <!-- Notifikasi -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Tabel -->
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Ruangan</th>
                        <th>Nama Peminjam</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen Pengampu</th>
                        <th>Jam Pinjam</th>
                        <th>Jam Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->nama_ruangan }}</td>
                        <td>{{ $r->nama_peminjam }}</td>
                        <td>{{ $r->mata_kuliah }}</td>
                        <td>{{ $r->dosen_pengampu }}</td>
                        <td>{{ $r->jam_pinjam }}</td>
                        <td>{{ $r->jam_kembali }}</td>

                        <td>
                            @if($r->status == 'menunggu')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif($r->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>

                        <td>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $r->peminjaman_ruangan_id }}">
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="modalHapus{{ $r->peminjaman_ruangan_id }}">
                        <div class="modal-dialog">
                            <form method="POST" action="/peminjaman/ruangan/delete/{{ $r->peminjaman_ruangan_id }}">
                                @csrf
                                @method('DELETE')

                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <h5>Konfirmasi Hapus</h5>
                                    </div>

                                    <div class="modal-body">
                                        Yakin ingin menghapus pengajuan untuk ruangan <b>{{ $r->nama_ruang }}</b>?
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    @endforeach
                </tbody>

            </table>

        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form action="{{ route('pinjam.ruangan.store') }}" method="POST">
            @csrf

            <div class="modal-content text-dark">

                <div class="modal-header">
                    <h5>Ajukan Peminjaman Ruangan</h5>
                </div>

                <div class="modal-body">

                    <label>Nama Ruangan</label>
                    <input type="text" name="nama_ruangan" class="form-control" required>

                    <label class="mt-3">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control" required>

                    <label class="mt-3">Mata Kuliah</label>
                    <input type="text" name="mata_kuliah" class="form-control" required>

                    <label class="mt-3">Dosen Pengampu</label>
                    <input type="text" name="dosen_pengampu" class="form-control" required>

                    <label class="mt-3">Jam Pinjam</label>
                    <input type="datetime-local" name="jam_pinjam" class="form-control" required>

                    <label class="mt-3">Jam Kembali</label>
                    <input type="datetime-local" name="jam_kembali" class="form-control" required>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                </div>

            </div>

        </form>
    </div>
</div>

@endsection
