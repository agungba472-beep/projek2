@extends('layouts.v_template')

@section('content')

<div class="container mt-4">
    <div class="card shadow">
    <div class="card-header d-flex justify-content-between">
        <h4>Peminjaman Fasilitas</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
            + Tambah Peminjaman
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Menampilkan Notifikasi dari Controller --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Fasilitas</th>
                        <th>Jam Pinjam</th>
                        <th>Jam Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->nama_peminjam }}</td>

                        <td>
                            {{-- Menampilkan fasilitas yang dipinjam --}}
                            @if(isset($d->detail))
                                @foreach($d->detail as $det)
                                    • **{{ $det->nama_fasilitas }}** ({{ $det->jumlah }}) <br>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $d->jam_pinjam }}</td>
                        <td>{{ $d->jam_kembali ?? '-' }}</td>

                        <td>
                            {{-- LOGIKA STATUS: DIPINJAM / DIKEMBALIKAN --}}
                            @if(strtolower($d->status) == 'dipinjam')
                                <span class="badge bg-success">Dipinjam</span>
                            @elseif(strtolower($d->status) == 'dikembalikan')
                                <span class="badge bg-info text-dark">Dikembalikan</span>
                            @else
                                {{-- Status lainnya seperti Ditolak/Menunggu (Jika ada data lama) --}}
                                <span class="badge bg-secondary">{{ strtoupper($d->status) }}</span>
                            @endif
                        </td>

                        <td>
                            {{-- LOGIKA AKSI: HANYA MUNCUL JIKA STATUS DIPINJAM --}}
                            @if(strtolower($d->status) == 'dipinjam')
                                <button class="btn btn-success btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#modalKembali{{ $d->peminjaman_fasilitas_id }}">
                                    Kembalikan
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalBatalkan{{ $d->peminjaman_fasilitas_id }}">
                                    Batalkan
                                </button>
                            @else
                                <span class="text-muted">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    
                    {{-- ======================================================= --}}
                    {{-- MODAL KEMBALIKAN FASILITAS --}}
                    {{-- ======================================================= --}}
                    <div class="modal fade" id="modalKembali{{ $d->peminjaman_fasilitas_id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('pinjam.fasilitas.kembalikan', $d->peminjaman_fasilitas_id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Pengembalian Fasilitas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Yakin fasilitas yang dipinjam sudah dikembalikan?
                                        <p class="mt-2 text-primary">Jam Kembali akan dicatat otomatis saat ini (Server Time).</p>
                                        <label class="mt-3">Upload Bukti Foto Pengembalian</label>
                                        <input type="file" name="bukti_foto" accept="image/*" class="form-control @error('bukti_foto') is-invalid @enderror" required>
                                        @error('bukti_foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Konfirmasi & Kembalikan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    {{-- ======================================================= --}}
                    {{-- MODAL BATALKAN PEMINJAMAN --}}
                    {{-- ======================================================= --}}
                    <div class="modal fade" id="modalBatalkan{{ $d->peminjaman_fasilitas_id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('pinjam.fasilitas.batalkan', $d->peminjaman_fasilitas_id) }}">
                                @csrf
                                @method('DELETE')
                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Pembatalan Peminjaman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Yakin ingin membatalkan peminjaman fasilitas ini?
                                        <input type="hidden" name="id" value="{{ $d->peminjaman_fasilitas_id }}">
                                        <p class="text-danger mt-2">Fasilitas akan tersedia kembali setelah dibatalkan.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-danger">Batalkan Peminjaman</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada riwayat peminjaman fasilitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
    </div>
</div>

---

<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('pinjam.fasilitas.store') }}" method="POST">
            @csrf
            {{-- Menggunakan hidden field user_id untuk keamanan --}}
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Peminjaman Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" name="nama_peminjam" value="{{ Auth::user()->name ?? 'Nama Default' }}" class="form-control" required>
                    </div>

                    <h6 class="mb-2">Pilih Fasilitas</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input fasilitasCheck" type="checkbox" value="Proyektor" name="fasilitas[]" data-target="#j1">
                                <label class="form-check-label">Proyektor</label>
                            </div>
                            <input type="number" id="j1" name="jumlah[]" class="form-control jumlahInput mt-1" placeholder="Jumlah" disabled min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input fasilitasCheck" type="checkbox" value="Terminal" name="fasilitas[]" data-target="#j2">
                                <label class="form-check-label">Terminal</label>
                            </div>
                            <input type="number" id="j2" name="jumlah[]" class="form-control jumlahInput mt-1" placeholder="Jumlah" disabled min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input fasilitasCheck" type="checkbox" value="Kabel HDMI" name="fasilitas[]" data-target="#j3">
                                <label class="form-check-label">Kabel HDMI</label>
                            </div>
                            <input type="number" id="j3" name="jumlah[]" class="form-control jumlahInput mt-1" placeholder="Jumlah" disabled min="1">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Jam Pinjam</label>
                            <input type="datetime-local" name="jam_pinjam" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jam Kembali (Perkiraan)</label>
                            <input type="datetime-local" name="jam_kembali_target" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Pinjam Sekarang</button> {{-- Mengganti Simpan menjadi Pinjam Sekarang --}}
                </div>
            </div>
        </form>
    </div>
</div>

---

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.fasilitasCheck').forEach(cb => {
        cb.addEventListener('change', function() {
            const target = document.querySelector(this.dataset.target);
            if (this.checked) {
                target.disabled = false;
                target.focus();
            } else {
                target.value = "";
                target.disabled = true;
            }
        });
    });
});
</script>

@endsection