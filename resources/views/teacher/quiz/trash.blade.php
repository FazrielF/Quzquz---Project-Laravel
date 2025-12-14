@extends('templates.sidebar')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">Quiz Terhapus</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quizzes as $q)
            <tr>
                <td>{{ $q->title }}</td>
                <td>{{ $q->description }}</td>
                <td>{{ $q->time_limit ? $q->time_limit . " menit" : '-' }}</td>
                <td class="d-flex gap-2">
                    <form action="{{ route('teacher.quizzes.restore',$q->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-sm">Restore</button>
                    </form>
                    <form action="{{ route('teacher.quizzes.delete_permanent',$q->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete Permanent</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada quiz di sampah</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
