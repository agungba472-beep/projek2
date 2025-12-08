@extends('layouts.v_template')

@section('content')

<div class="container mt-4">
    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">
            <h4>Peminjaman Ruangan</h4>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                Ajukan Peminjaman
            </button>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Ruangan</th>
                        <th>Nama Peminjam</th>
                        <th>Mata Kuliah</th>
                        <!-- <th>Dosen Pengampu</th> -->
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
                            {{-- LOGIKA BARU UNTUK STATUS --}}
                            @if(strtolower($r->status) == 'dipinjam')
                                <span class="badge bg-success">Dipinjam</span>
                            @elseif(strtolower($r->status) == 'dikembalikan')
                                <span class="badge bg-info text-dark">Dikembalikan</span>
                            @else
                                <span class="badge bg-secondary">Tidak Dikenal</span>
                            @endif
                        </td>
                        <td>
                            {{-- AKSI BARU --}}
                            @if(strtolower($r->status) == 'dipinjam')
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalKembali{{ $r->peminjaman_ruangan_id }}">
                                    Kembalikan
                                </button>
                                
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $r->peminjaman_ruangan_id }}">
                                    Batalkan
                                </button>
                            @endif
                            
                            @if(strtolower($r->status) == 'dikembalikan')
                                {{-- Aksi lain jika sudah dikembalikan, misalnya tombol Lihat Bukti --}}
                                <span class="text-muted">Selesai</span>
                            @endif
                        </td>
                    </tr>

                    {{-- ======================================================= --}}
                    {{-- MODAL HAPUS/BATALKAN --}}
                    {{-- ======================================================= --}}
                    <div class="modal fade" id="modalHapus{{ $r->peminjaman_ruangan_id }}">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('dosen.pinjam.ruangan.delete') }}">
                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="id" value="{{ $r->peminjaman_ruangan_id }}">

                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <h5>Konfirmasi Pembatalan</h5>
                                    </div>

                                    <div class="modal-body">
                                        Yakin ingin membatalkan peminjaman ruangan <b>{{ $r->nama_ruangan }}</b>?
                                        <p class="text-danger mt-2">Pembatalan hanya bisa dilakukan jika status masih **Dipinjam**.</p>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-danger">Batalkan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    {{-- ======================================================= --}}
                    {{-- MODAL KEMBALIKAN (Jam Kembali Dihilangkan) --}}
                    {{-- ======================================================= --}}
                    <div class="modal fade" id="modalKembali{{ $r->peminjaman_ruangan_id }}">
                        <div class="modal-dialog">
                            <form method="POST" action="/dosen/peminjaman/ruangan/kembalikan/{{ $r->peminjaman_ruangan_id }}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content text-dark">

                                    <div class="modal-header">
                                        <h5>Konfirmasi Pengembalian Ruangan</h5>
                                    </div>

                                    <div class="modal-body">
                                        Ruangan **{{ $r->nama_ruangan }}** akan dicatat dikembalikan pada **waktu saat ini** (Server Time).
                                        <p class="mt-3 text-primary">Jam Kembali akan dicatat otomatis.</p>
                                        
                                        <label class="mt-3">Upload Bukti Foto</label>
                                        <input type="file" name="bukti_foto" accept="image/*" class="form-control" required>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Konfirmasi & Kembalikan</button>
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

{{-- ======================================================= --}}
{{-- MODAL TAMBAH (Mengubah Tombol 'Ajukan' menjadi 'Pinjam') --}}
{{-- ======================================================= --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form action="{{ route('dosen.pinjam.ruangan.store') }}" method="POST">
            @csrf

            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5>Peminjaman Ruangan Baru</h5>
                </div>

                <div class="modal-body">

                    <label>Nama Ruangan</label>
                    <input type="text" name="nama_ruangan" class="form-control" required>

                    <label class="mt-3">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control" required>

                    <label class="mt-3">Mata Kuliah</label>
                    <input type="text" name="mata_kuliah" class="form-control" required>

                    <!-- <label class="mt-3">Dosen Pengampu</label>
                    <input type="text" name="dosen_pengampu" class="form-control" required> -->

                    <label class="mt-3">Jam Pinjam</label>
                    <input type="datetime-local" name="jam_pinjam" class="form-control" required>
                    
                    <label class="mt-3">Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control"></textarea>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Pinjam Sekarang</button>
                </div>

            </div>

        </form>
    </div>
</div>

@endsection