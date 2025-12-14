@extends('templates.sidebar')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h3 class="fw-bold">Tambah Soal untuk Quiz: {{ $quiz->title }}</h3>
        <p class="text-muted">Deskripsi: {{ $quiz->description }}</p>
        <p class="text-muted">Durasi Quiz: {{ $quiz->time_limit }} menit</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('teacher.question.store' , $quiz->id) }}" method="POST">
        @csrf

        <div id="question-container">

            <!-- Soal pertama -->
            <div class="question-item p-3 border rounded mb-3">
                <label>Soal</label>
                <input type="text" name="questions[0][question_text]" class="form-control mb-2" required>

                <label>Tipe Soal</label>
                <select name="questions[0][question_type]" class="form-control mb-3" onchange="toggleOption(this,0)">
                    <option value="mcq">Multiple Choice</option>
                    <option value="essay">Essay</option>
                </select>

                <div id="options-0">
                    <label>Opsi Jawaban</label>
                    <input type="text" name="questions[0][options][]" class="form-control mb-1" placeholder="A" required>
                    <input type="text" name="questions[0][options][]" class="form-control mb-1" placeholder="B" required>
                    <input type="text" name="questions[0][options][]" class="form-control mb-1" placeholder="C" required>
                    <input type="text" name="questions[0][options][]" class="form-control mb-3" placeholder="D" required>

                    <label>Jawaban Benar</label>
                    <input type="text" name="questions[0][correct_answer]" class="form-control mb-3" placeholder="contoh: A" required>
                </div>

                <label>Waktu Soal (detik)</label>
                <input type="number" name="questions[0][time_limit]" class="form-control" placeholder="30" required>
            </div>

        </div>

        <button type="button" onclick="addQuestion()" class="btn btn-primary">Tambah Soal Baru</button>
        <button type="submit" class="btn btn-success mt-2">Simpan Semua Soal</button>
    </form>
</div>
@endsection

@push('script')
<script>
let index = 1

function addQuestion() {
    const container = document.getElementById('question-container')

    container.insertAdjacentHTML('beforeend', `
        <div class="question-item p-3 border rounded mb-3">
            <label>Soal</label>
            <input type="text" name="questions[${index}][question_text]" class="form-control mb-2" required>

            <label>Tipe Soal</label>
            <select name="questions[${index}][question_type]" class="form-control mb-3" onchange="toggleOption(this,${index})">
                <option value="mcq">Multiple Choice</option>
                <option value="essay">Essay</option>
            </select>

            <div id="options-${index}">
                <label>Opsi Jawaban</label>
                <input type="text" name="questions[${index}][options][]" class="form-control mb-1" placeholder="A" required>
                <input type="text" name="questions[${index}][options][]" class="form-control mb-1" placeholder="B" required>
                <input type="text" name="questions[${index}][options][]" class="form-control mb-1" placeholder="C" required>
                <input type="text" name="questions[${index}][options][]" class="form-control mb-3" placeholder="D" required>

                <label>Jawaban Benar</label>
                <input type="text" name="questions[${index}][correct_answer]" class="form-control mb-3" placeholder="contoh: A" required>
            </div>

            <label>Waktu Soal (detik)</label>
            <input type="number" name="questions[${index}][time_limit]" class="form-control" placeholder="30" required>
        </div>
    `)

    index++
}

function toggleOption(select, i) {
    const section = document.getElementById(`options-${i}`)
    if (select.value === 'essay') {
        section.style.display = 'none'
    } else {
        section.style.display = 'block'
    }
}


</script>
@endpush