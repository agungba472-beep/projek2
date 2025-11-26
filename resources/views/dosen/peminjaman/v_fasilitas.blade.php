@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
    <!-- Header -->
    <div class="card-header d-flex justify-content-between">
        <h4>Peminjaman Fasilitas</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
            + Tambah Peminjaman
        </button>
    </div>

    <!-- Card Tabel -->
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Peminjam</th>
                        <th>Fasilitas</th>
                        <th>Jam Pinjam</th>
                        <th>Jam Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr>
                        <td>{{ $d->nama_peminjam }}</td>

                        <td>
                            @foreach($d->detail as $det)
                                • {{ $det->nama_fasilitas }} ({{ $det->jumlah }}) <br>
                            @endforeach
                        </td>

                        <td>{{ $d->jam_pinjam }}</td>
                        <td>{{ $d->jam_kembali }}</td>

                        <td>
                            <span class="badge 
                                @if($d->status=='menunggu') bg-warning text-dark
                                @elseif($d->status=='disetujui') bg-success
                                @else bg-danger
                                @endif">
                                {{ $d->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>
    </div>
</div>


<!-- ============================
     MODAL TAMBAH PEMINJAMAN
=============================== -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('pinjam.fasilitas.store') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Peminjaman Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Nama Peminjam -->
                    <div class="mb-3">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" name="nama_peminjam" value="{{ Auth::user()->name }}"
                               class="form-control" required>
                    </div>

                    <!-- PILIH FASILITAS -->
                    <h6 class="mb-2">Pilih Fasilitas</h6>

                    <div class="row">

                        <!-- PROYEKTOR -->
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input fasilitasCheck"
                                       type="checkbox" value="Proyektor"
                                       name="fasilitas[]" data-target="#j1">
                                <label class="form-check-label">Proyektor</label>
                            </div>
                            <input type="number" id="j1" name="jumlah[]"
                                   class="form-control jumlahInput mt-1"
                                   placeholder="Jumlah" disabled min="1">
                        </div>

                        <!-- TERMINAL -->
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input fasilitasCheck"
                                       type="checkbox" value="Terminal"
                                       name="fasilitas[]" data-target="#j2">
                                <label class="form-check-label">Terminal</label>
                            </div>
                            <input type="number" id="j2" name="jumlah[]"
                                   class="form-control jumlahInput mt-1"
                                   placeholder="Jumlah" disabled min="1">
                        </div>

                        <!-- HDMI -->
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input fasilitasCheck"
                                       type="checkbox" value="Kabel HDMI"
                                       name="fasilitas[]" data-target="#j3">
                                <label class="form-check-label">Kabel HDMI</label>
                            </div>
                            <input type="number" id="j3" name="jumlah[]"
                                   class="form-control jumlahInput mt-1"
                                   placeholder="Jumlah" disabled min="1">
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- JAM PINJAM -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Jam Pinjam</label>
                            <input type="time" name="jam_pinjam" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Jam Kembali</label>
                            <input type="time" name="jam_kembali" class="form-control" required>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>


<!-- Script: Enable / Disable Input Jumlah -->
<script>
document.querySelectorAll('.fasilitasCheck').forEach(cb => {
    cb.addEventListener('change', function() {
        const target = document.querySelector(this.dataset.target);

        if (this.checked) {
            target.disabled = false;
        } else {
            target.value = "";
            target.disabled = true;
        }
    });
});
</script>

@endsection
