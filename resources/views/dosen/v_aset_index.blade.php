@extends('layouts.v_template')

@section('content')
<div class="container-fluid">
    <h3>Daftar Aset JTIK</h3>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Aset</th>
                            <th>Jenis</th>
                            <th>Ruangan</th>
                            <th>Kepala Lab</th> <th>Tahun Pengadaan</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th>QR Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aset as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenis ?? '-' }}</td>
                            <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                            <td>{{ $item->ruangan->kepalaLab->nama ?? '-' }}</td> 
                            <td>{{ $item->tahun_pengadaan ?? '-' }}</td>
                            <td>{{ $item->kondisi }}</td>
                            <td>{{ $item->status }}</td>
                            <td>
                                @if($item->qr_code)
                                    <a href="{{ route('qr.download', $item->aset_id) }}" class="btn btn-sm btn-info" target="_blank">
                                        QR
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection