@extends('templates.sidebar')

@section('content')
<div class="container my-5">
    <div class="p-4 rounded shadow-sm" style="background:#f7f3f9;">
        <h4 class="fw-bold mb-4 text-dark">Edit Quiz</h4>

        <form action="{{ route('teacher.quizzes.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Judul Quiz</label>
                <input type="text" name="title" value="{{ $quiz->title }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description" rows="3" class="form-control">{{ $quiz->description }}</textarea>
            </div>

            <div class="mb-3">
                <label>Waktu (Menit)</label>
                <input type="number" name="time_limit" value="{{ $quiz->time_limit }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Gambar</label><br>
                @if($quiz->image)
                <img src="{{ asset('storage/'.$quiz->image) }}" width="100" class="mb-2 rounded">
                @endif
                <input type="file" name="image" class="form-control">
            </div>

            <button class="btn" style="background:#3b142a; color:#fff;">Update</button>
            <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
