@extends('layouts.v_template')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">
            <h4>Proses Komplain</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.komplain.assign', $komplain->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>User</label>
                    <input type="text" class="form-control" value="{{ $komplain->user->name }}" readonly>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea class="form-control" readonly>{{ $komplain->deskripsi }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Pilih Teknisi</label>
                    <select name="teknisi_id" class="form-select" required>
                        <option value="">-- pilih teknisi --</option>
                        @foreach ($teknisi as $t)
                            <option value="{{ $t->teknisi_id }}">{{ $t->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Level Kerusakan</label>
                    <select name="level_kerusakan" class="form-select" required>
                        <option value="minor">Minor (SLA 1.5 jam)</option>
                        <option value="major">Major (SLA 1 hari)</option>
                    </select>
                </div>

                <button class="btn btn-primary">Assign Teknisi</button>
            </form>
        </div>
    </div>
</div>
@endsection
