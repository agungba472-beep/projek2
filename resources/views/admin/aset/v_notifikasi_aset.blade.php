@extends('layouts.v_template')

@section('content')

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0">Notifikasi Aset</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Aset</th>
                        <th>Pesan Notifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($notifikasi as $index => $notif)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $notif['aset']->nama }}</td>
                            <td>{{ $notif['pesan'] }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $notif['aksi'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Tidak ada notifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection
