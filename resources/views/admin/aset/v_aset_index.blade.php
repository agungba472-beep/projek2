@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">
            <h4>Data Aset</h4>
            <a href="{{ route('aset.tambah') }}" class="btn btn-primary">Add Data</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped" id="asetTable">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Status</th>

                        {{-- Kolom baru --}}
                        <th>Maintenance Terakhir</th>
                        <th>Kartu Kontrol</th>

                        <th>Aksi</th>
                        <th>QR</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($aset as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->jenis }}</td>
                        <td>{{ $row->lokasi }}</td>
                        <td>{{ $row->kondisi }}</td>
                        <td>{{ $row->status }}</td>

                        {{-- maintenance terakhir --}}
                        <td>
                            @if($row->maintenance_terakhir)
                                {{ \Carbon\Carbon::parse($row->maintenance_terakhir->tanggal_dijadwalkan)->format('d M Y') }}
                            @else
                                <span class="text-muted">Belum ada</span>
                            @endif
                        </td>

                        {{-- tombol ke maintenance --}}
                        <td>
                            <a href="{{ route('maintenance.listAset', $row->aset_id) }}" 
                               class="btn btn-info btn-sm">
                                Lihat
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('aset.edit', $row->aset_id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('aset.delete', $row->aset_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin dihapus?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                        <td>
  <a href="{{ route('qr.download', $row->aset_id) }}" class="btn btn-sm btn-primary">
    Download QR
</a>


</td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection
