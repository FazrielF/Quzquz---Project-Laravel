@extends('templates.sidebar')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">Edit Soal pada Quiz: {{ $quiz->title }}</h3>

    <form action="{{ route('teacher.question.update',[$quiz->id,$question->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Soal</label>
        <input type="text" name="question_text" class="form-control mb-2"
               value="{{ $question->question_text }}" required>

        <label>Tipe Soal</label>
        <select name="question_type" class="form-control mb-3" onchange="toggleOptionEdit(this)">
            <option value="mcq" {{ $question->question_type=='mcq'?'selected':'' }}>Multiple Choice</option>
            <option value="essay" {{ $question->question_type=='essay'?'selected':'' }}>Essay</option>
        </select>

        <div id="optionBox" style="{{ $question->question_type=='essay'?'display:none':'' }}">
            <label>Opsi</label>
            @foreach($options as $o)
            <input type="text" name="options[]" class="form-control mb-1" value="{{ $o->option_text }}">
            @endforeach

            <label>Jawaban Benar</label>
            <input type="text" name="correct_answer" class="form-control mb-3" 
                   value="{{ $correct_answer }}">
        </div>

        <label>Waktu Soal (detik)</label>
        <input type="number" name="time_limit" class="form-control mb-3"
               value="{{ $question->time_limit }}" required>

        <button class="btn btn-success">Update</button>
    </form>

</div>

<script>
function toggleOptionEdit(select){
    const box=document.getElementById('optionBox')
    box.style.display=select.value==='essay'?'none':'block'
}
</script>
@endsection
