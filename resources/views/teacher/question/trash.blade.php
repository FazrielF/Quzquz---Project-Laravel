@extends('templates.sidebar')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-3">Trash Soal</h3>
    <form method="GET" action="{{ route('teacher.question.trash') }}" class="mb-3">
        <label>Pilih Quiz</label>
        <select name="quiz_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Pilih Quiz --</option>
            @foreach($quizzes as $q)
                <option value="{{ $q->id }}" {{ request('quiz_id')==$q->id?'selected':'' }}>
                    {{ $q->title }}
                </option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('teacher.question.index',['quiz_id'=>request('quiz_id')]) }}" class="btn btn-secondary mb-3">Kembali</a>

    @if(request('quiz_id'))
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Soal</th>
                <th>Tipe</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trashQuestions as $t)
            <tr>
                <td>{{ $t->question_text }}</td>
                <td>{{ strtoupper($t->question_type) }}</td>
                <td class="d-flex gap-2">
                    <form action="{{ route('teacher.question.restore',$t->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-sm">Restore</button>
                    </form>
                    <form action="{{ route('teacher.question.delete_permanent',$t->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete Permanent</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
