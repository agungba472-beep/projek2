@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between">
            <h4>Maintenance Aset</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">+ Tambah Maintenance</button>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Aset</th>
                        <th>Jenis</th>
                        <th>Tgl Dijadwalkan</th>
                        <th>Status</th>
                        <th>Teknisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $m)
                    <tr>
                        <td>{{ $m->aset->nama ?? '-' }}</td>
                        <td>{{ $m->jenis }}</td>
                        <td>{{ $m->tanggal_dijadwalkan }}</td>
                        <td>
                            <span class="badge 
                                @if($m->status=='Terjadwal') bg-warning text-dark
                                @elseif($m->status=='Proses') bg-primary
                                @elseif($m->status=='Selesai') bg-success
                                @else bg-danger
                                @endif">
                                {{ $m->status }}
                            </span>
                        </td>
                        <td>{{ $m->teknisi->username ?? '-' }}</td>
                        <td>
                            <a href="{{ route('maintenance.detail', $m->maintenance_id) }}" class="btn btn-info btn-sm">Detail</a>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $m->maintenance_id }}">Edit</button>
                            <a href="{{ route('maintenance.delete', $m->maintenance_id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Hapus</a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT -->
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
                                            <label>Aset</label>
                                            <select name="aset_id" class="form-control" required>
                                                @foreach($aset as $a)
                                                <option value="{{ $a->aset_id }}" @if($a->aset_id==$m->aset_id) selected @endif>{{ $a->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Jenis</label>
                                            <select name="jenis" class="form-select">
                                                <option {{ $m->jenis=='Rutin' ? 'selected':'' }}>Rutin</option>
                                                <option {{ $m->jenis=='Insiden' ? 'selected':'' }}>Insiden</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Tanggal Dijadwalkan</label>
                                            <input type="date" name="tanggal_dijadwalkan" class="form-control" value="{{ $m->tanggal_dijadwalkan }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Status</label>
                                            <select name="status" class="form-select">
                                                <option>Terjadwal</option>
                                                <option {{ $m->status=='Proses' ? 'selected':'' }}>Proses</option>
                                                <option {{ $m->status=='Selesai' ? 'selected':'' }}>Selesai</option>
                                                <option {{ $m->status=='Gagal' ? 'selected':'' }}>Gagal</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Catatan</label>
                                            <textarea name="catatan" class="form-control">{{ $m->catatan }}</textarea>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Maintenance</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Aset</label>
                        <select name="aset_id" class="form-control" required>
                            @foreach($aset as $a)
                            <option value="{{ $a->aset_id }}">{{ $a->nama }}</option>
                            @endforeach
                        </select>
                    </div>
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
