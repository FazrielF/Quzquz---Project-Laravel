@extends('templates.sidebar')

@section('content')
<div class="container my-5">
    <div class="p-4 rounded shadow-sm" style="background:#f7f3f9;">
        <h4 class="fw-bold mb-4 text-dark">Tambah Quiz</h4>

        <form action="{{ route('teacher.quizzes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Judul Quiz</label>
                <input type="text" name="title" class="form-control" placeholder="Masukkan judul quiz">
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description" rows="3" class="form-control" placeholder="Masukkan deskripsi"></textarea>
            </div>

            <div class="mb-3">
                <label>Waktu Pengerjaan (Menit)</label>
                <input type="number" name="time_limit" class="form-control" placeholder="Contoh 10">
            </div>

            <div class="mb-3">
                <label>Upload Gambar Cover</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button class="btn" style="background:#3b142a; color:#fff;">Simpan</button>
            <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
