@extends('layouts.v_template')

@section('content')

<h3>Daftar Komplain</h3>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $k)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $k->user_id }}</td>
            <td>{{ $k->kategori }}</td>
            <td>{{ Str::limit($k->deskripsi, 30) }}</td>
            <td>
                <span class="badge bg-info">{{ $k->status }}</span>
            </td>
            <td>{{ $k->created_at }}</td>
            <td>
                <a href="{{ route('admin.komplain.show', $k->komplain_id) }}" class="btn btn-sm btn-primary">Detail</a>
                 <a href="{{ route('admin.komplain.proses', $k->komplain_id) }}" class="btn btn-primary btn-sm">Proses</a>
                <form action="{{ route('admin.komplain.destroy', $k->komplain_id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus komplain ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
