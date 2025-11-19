@extends('layouts.v_template')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">Laporan Aset</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>no</th>
                <th>Nama Aset</th>
                <th>Jenis</th>
                <th>Tanggal Input</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->jenis }}</td>
                <td>{{ $d->tanggal_input }}</td>
                <td>
                    <a href="{{ route('laporan.inventaris.show', $d->aset_id) }}"  class="btn btn-primary btn-sm">
                        Lihat Laporan
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
