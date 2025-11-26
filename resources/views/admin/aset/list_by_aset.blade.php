@extends('layouts.v_template')

@section('content')
<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">

    <div>
        <h4 class="mb-0">Maintenance Aset: <strong>{{ $aset->nama }}</strong></h4>

        <a href="{{ route('aset.index') }}" class="btn btn-secondary btn-sm mt-2">
            ← Kembali
        </a>
    </div>

    <button class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#addMaintenanceModal">
        + Tambah Maintenance
    </button>

</div>


        <div class="card-body">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Tgl Dijadwalkan</th>
                        <th>Status</th>
                        <th>Teknisi</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($maintenance as $m)
                    <tr>
                        <td>{{ $m->jenis }}</td>

                        <td>{{ $m->tanggal_dijadwalkan }}</td>

                        <td>
                            <span class="badge
                                @if($m->status=='Terjadwal') bg-warning text-dark
                                @elseif($m->status=='Proses') bg-primary
                                @elseif($m->status=='Selesai') bg-success
                                @else bg-danger
                                @endif ">
                                {{ $m->status }}
                            </span>
                        </td>

                        <td>{{ $m->teknisi->username ?? '-' }}</td>

                        <td>{{ $m->catatan ?? '-' }}</td>

                        <td>
                            <a href="{{ route('maintenance.detail', $m->maintenance_id) }}" 
                            class="btn btn-info btn-sm">
                            Detail
                            </a>

                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#edit{{ $m->maintenance_id }}">
                                Edit
                            </button>

                            <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#delete{{ $m->maintenance_id }}">
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <!-- ============================= -->
                    <!-- MODAL EDIT MAINTENANCE        -->
                    <!-- ============================= -->
                    <div class="modal fade" id="edit{{ $m->maintenance_id }}">
                        <div class="modal-dialog">
                            <form action="{{ route('maintenance.update', $m->maintenance_id) }}" method="POST">
                                @csrf
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5>Edit Maintenance</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label>Jenis</label>
                                            <select name="jenis" class="form-select">
                                                <option value="Rutin" {{ $m->jenis=='Rutin'?'selected':'' }}>
                                                    Rutin
                                                </option>
                                                <option value="Insiden" {{ $m->jenis=='Insiden'?'selected':'' }}>
                                                    Insiden
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Tanggal Dijadwalkan</label>
                                            <input type="date"
                                                name="tanggal_dijadwalkan"
                                                class="form-control"
                                                value="{{ $m->tanggal_dijadwalkan }}"
                                                required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Status</label>
                                            <select name="status" class="form-select">
                                                <option value="Terjadwal" {{ $m->status=='Terjadwal'?'selected':'' }}>Terjadwal</option>
                                                <option value="Proses" {{ $m->status=='Proses'?'selected':'' }}>Proses</option>
                                                <option value="Selesai" {{ $m->status=='Selesai'?'selected':'' }}>Selesai</option>
                                                <option value="Gagal" {{ $m->status=='Gagal'?'selected':'' }}>Gagal</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Teknisi</label>
                                            <select name="teknisi_id" class="form-select">
                                            @foreach($teknisi as $t)
                                                <option value="{{ $t->id }}" 
                                                    @if($t->id == $m->teknisi_id) selected @endif>
                                                    {{ $t->username }}
                                                </option>
                                            @endforeach
                                        </select>

                                        </div>

                                        <div class="mb-3">
                                            <label>Catatan</label>
                                            <textarea class="form-control" name="catatan">{{ $m->catatan }}</textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ============================= -->
                    <!-- MODAL HAPUS                   -->
                    <!-- ============================= -->
                    <div class="modal fade" id="delete{{ $m->maintenance_id }}">
                        <div class="modal-dialog">
                            <form action="{{ route('maintenance.delete', $m->maintenance_id) }}" method="POST">
                                @csrf
                                @method('DELETE')


                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5>Hapus Maintenance</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        Yakin ingin menghapus data ini?
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-danger">Hapus</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data maintenance</td>
                    </tr>

                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>



<!-- ================================================= -->
<!-- MODAL TAMBAH MAINTENANCE                         -->
<!-- ================================================= -->
<div class="modal fade" id="addMaintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Tambah Maintenance untuk {{ $aset->nama }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="aset_id" value="{{ $aset->aset_id }}">

                    <div class="mb-3">
                        <label>Jenis</label>
                        <select name="jenis" class="form-select">
                            <option value="Rutin">Rutin</option>
                            <option value="Insiden">Insiden</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Dijadwalkan</label>
                        <input type="date" name="tanggal_dijadwalkan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Teknisi</label>
                    <select name="teknisi_id" class="form-select" required>
                        <option value="" disabled selected>Pilih Teknisi</option> 
                        @foreach($teknisi as $t)
                        <option value="{{ $t->user_id }}">{{ $t->username }}</option>
                        @endforeach
                    </select>
                    </div>

                    <div class="mb-3">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection
