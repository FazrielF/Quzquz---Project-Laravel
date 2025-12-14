@extends('templates.sidebar')

@section('content')
<div class="container my-5">
    <div class="p-4 rounded shadow-sm" style="background:#f7f3f9;">
        <h4 class="fw-bold mb-4 text-dark">Tambah User</h4>

        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 5 karakter">
            </div>

            <button class="btn" style="background:#3b142a; color:#fff;">Simpan</button>
            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
