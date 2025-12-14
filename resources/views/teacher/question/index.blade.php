@extends('templates.sidebar')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">Kelola Soal</h3>

    <form method="GET" action="{{ route('teacher.question.index') }}" class="mb-3">
        <label>Pilih Quiz</label>
        <select name="quiz_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Pilih Quiz --</option>
            @foreach($quizzes as $q)
                <option value="{{ $q->id }}" {{ request('quiz_id')==$q->id ? 'selected':'' }}>
                    {{ $q->title }}
                </option>
            @endforeach
        </select>
    </form>

    @if(request('quiz_id'))
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('teacher.question.create',request('quiz_id')) }}" class="btn btn-success">Tambah Soal</a>
            <a href="{{ route('teacher.question.trash',request('quiz_id')) }}" class="btn btn-secondary">Trash</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="50%">Soal</th>
                    <th>Tipe</th>
                    <th>Waktu</th>
                    <th width="18%">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($question as $q)
                    <tr>
                        <td>{{ $q->question_text }}</td>
                        <td>{{ strtoupper($q->question_type) }}</td>
                        <td>{{ $q->time_limit }} dtk</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('teacher.question.edit',[request('quiz_id'),$q->id]) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('teacher.question.delete',[request('quiz_id'),$q->id]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data soal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>
@endsection
