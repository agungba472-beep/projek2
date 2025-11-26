@extends('layouts.v_template')

@section('content')
<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0">Laporan Inventaris Aset</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Aset</th>
                        <th>Jenis</th>
                        <th>Tanggal Input</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->jenis }}</td>
                        <td>{{ $d->tanggal_input }}</td>

                        <td>
                            <a 
                                href="{{ url('/admin/aset/laporan/'.$d->aset_id) }}" 
                                class="btn btn-primary btn-sm">
                                Lihat Laporan
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data aset.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
