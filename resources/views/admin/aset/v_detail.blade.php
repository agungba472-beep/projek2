@extends('layouts.v_template')

@section('content')
<div class="container mt-4">

    <a href="{{ url('/admin/aset/laporan') }}" class="btn btn-secondary btn-sm mb-3">
        ← Kembali
    </a>

    <h3 class="mb-3">Detail Laporan Aset</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5>Informasi Aset</h5>
            <table class="table table-sm">
                <tr>
                    <th>Nama Aset</th>
                    <td>{{ $aset->nama }}</td>
                </tr>
                <tr>
                    <th>Jenis</th>
                    <td>{{ $aset->jenis }}</td>
                </tr>
                <tr>
                    <th>Tanggal Peroleh</th>
                    <td>{{ $aset->tanggal_peroleh }}</td>
                </tr>
                <tr>
                    <th>Umur Maksimal</th>
                    <td>{{ $aset->umur_maksimal }} tahun</td>
                </tr>
                <tr>
                    <th>Kondisi</th>
                    <td>{{ $aset->kondisi }}</td>
                </tr>
            </table>
        </div>
    </div>
{{-- STATUS PEMUTIHAN --}}
<div class="card shadow-sm">
    <div class="card-body">

        <h5>Status Pemutihan</h5>

        {{-- Jika sudah pernah dipemutihkan --}}
        @if ($pemutihan['data_laporan'])
            <div class="alert alert-info">
                <strong>Aset ini sudah dipemutihkan sebelumnya.</strong> <br>
                Tanggal: {{ $pemutihan['data_laporan']->tanggal_pemutihan }} <br>
                Catatan: {{ $pemutihan['data_laporan']->catatan }}
            </div>

        {{-- Jika perlu pemutihan --}}
        @elseif ($pemutihan['perlu_pemutihan'])
            <div class="alert alert-danger">
                <strong>Perlu Pemutihan:</strong> {{ $pemutihan['alasan'] }}
            </div>

            {{-- Tombol Simpan Laporan --}}
            <form action="{{ route('laporan.storePemutihan', $aset->aset_id) }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="catatan" value="{{ $pemutihan['alasan'] }}">
                <button class="btn btn-primary">Simpan Pemutihan</button>
            </form>

        {{-- Jika tidak perlu --}}
        @else
            <div class="alert alert-success">
                <strong>Tidak Perlu Pemutihan:</strong> {{ $pemutihan['alasan'] }}
            </div>
        @endif

        <a href="{{ route('laporan.inventaris.export', $aset->aset_id) }}" 
            class="btn btn-success mt-2">
            Export Excel
        </a>

    </div>
</div>


        </div>
    </div>

</div>
@endsection
