@extends('templates.sidebar')

@section('content')
<div class="container my-5">
    <div class="p-4 rounded shadow-sm" style="background:#f7f3f9;">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark">Daftar Quiz</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('teacher.quizzes.trash') }}" class="btn" style="background:#234C6A; color:#fff;">Sampah</a>
                <a href="{{ route('teacher.quizzes.create') }}" class="btn" style="background:#1B3C53; color:#fff;">Tambah Quiz</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-light text-center">
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Judul</th>
                        <th>Deskripsi</th>
                        <th width="10%">Waktu</th>
                        <th width="15%">Gambar</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quizzes as $key => $q)
                    <tr>
                        <td class="text-center">{{ $key+1 }}</td>
                        <td>{{ $q->title }}</td>
                        <td>{{ Str::limit($q->description, 50) }}</td>
                        <td class="text-center">{{ $q->time_limit }} Menit</td>
                        <td class="text-center">
                            @if($q->image)
                                <img src="{{ asset('storage/'.$q->image) }}" width="60" class="rounded">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center d-flex justify-content-center gap-2">
                            <a href="{{ route('teacher.quizzes.edit', $q->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('teacher.quizzes.delete', $q->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
