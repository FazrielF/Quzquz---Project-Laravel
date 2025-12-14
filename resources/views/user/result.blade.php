@extends('templates.nav')

@section('content')
@php
    $quiz = $result->quiz ?? null;
    $score = $result->score ?? 0;
    $answers = $result->answers ?? collect();
    $totalQuestions = $quiz ? $quiz->questions->count() : 0;
    $correctAnswers = $answers->where('is_correct', true)->count();
    $wrongAnswers = $totalQuestions - $correctAnswers;
@endphp

<div class="result-header py-5 text-white" 
     style="background: linear-gradient(135deg, #1B3C53 0%, #234C6A 100%);">
    <div class="container text-center">
        <h1 class="fw-bold mb-3">Hasil Kuis Anda</h1>
        <p class="lead opacity-75">Berikut adalah hasil dari kuis yang telah Anda selesaikan</p>
    </div>
</div>

<div class="container my-5">
    <div class="card border-0 shadow-lg mb-5">
        <div class="card-body text-center py-5">
            <div class="position-relative d-inline-block mb-4">
                <div class="rounded-circle bg-info d-flex align-items-center justify-content-center" 
                     style="width: 180px; height: 180px; margin: 0 auto;">
                    <h1 class="text-white fw-bold display-4 mb-0">{{ $score }}%</h1>
                </div>
            </div>
            
            <h3 class="fw-bold mb-3" style="color: #1B3C53;">
                @if($score >= 80)
                    🎉 Selamat! Kerja Bagus!
                @elseif($score >= 60)
                    👍 Lumayan! Tetap Semangat!
                @else
                    💪 Jangan Menyerah! Coba Lagi!
                @endif
            </h3>
            
            <p class="text-muted mb-4">
                Anda telah menyelesaikan kuis "<strong>{{ $quiz->title ?? 'Quiz' }}</strong>"
            </p>
            
            <div class="row justify-content-center mt-4">
                <div class="col-md-3">
                    <div class="p-3 text-center">
                        <div class="display-6 fw-bold text-success">{{ $correctAnswers }}</div>
                        <div class="text-muted">Jawaban Benar</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 text-center">
                        <div class="display-6 fw-bold text-danger">{{ $wrongAnswers }}</div>
                        <div class="text-muted">Jawaban Salah</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 text-center">
                        <div class="display-6 fw-bold text-primary">{{ $totalQuestions }}</div>
                        <div class="text-muted">Total Soal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($answers->count() > 0)
    <div class="card border-0 shadow-lg">
        <div class="card-header py-3" style="background:#456882;">
            <h3 class="mb-0 text-white"><i class="fas fa-list-check me-2"></i> Review Jawaban</h3>
        </div>
        <div class="card-body">
            @foreach($answers as $index => $answer)
            @php
                $question = $answer->question;
                $userAnswerText = $answer->option ? $answer->option->option_text : $answer->answer_text;
                $correctOption = $question ? $question->options->where('is_correct', 1)->first() : null;
                $correctAnswerText = $correctOption ? $correctOption->option_text : 'Jawaban essay (perlu penilaian manual)';
            @endphp
            
            <div class="mb-4 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <h5 class="fw-bold">
                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                    {{ $question->question_text ?? 'Pertanyaan tidak ditemukan' }}
                </h5>
                
                <div class="p-3 rounded mb-2 {{ $answer->is_correct ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Jawaban Anda:</small>
                            <div class="fw-medium">{{ $userAnswerText ?? 'Tidak dijawab' }}</div>
                        </div>
                        <span class="badge {{ $answer->is_correct ? 'bg-success' : 'bg-danger' }}">
                            {{ $answer->is_correct ? 'Benar' : 'Salah' }}
                        </span>
                    </div>
                </div>
                
                @if(!$answer->is_correct && $correctOption)
                <div class="p-3 rounded bg-info bg-opacity-10">
                    <small class="text-muted">Jawaban Benar:</small>
                    <div class="fw-medium">{{ $correctAnswerText }}</div>
                </div>
                @endif
                
                @if($question && $question->question_type == 'essay')
                <div class="mt-2 p-2 rounded bg-warning bg-opacity-10">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Catatan:</small>
                    <div class="small">Jawaban essay memerlukan penilaian manual oleh pengajar.</div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <div class="text-center mt-5">
        @if($quiz)
        <a href="{{ route('user.quiz.play', $quiz->id) }}" class="btn btn-info px-4 me-2">
            <i class="fas fa-redo me-2"></i> Coba Lagi
        </a>
        @endif
        <a href="{{ route('home') }}" class="btn btn-outline-info px-4">
            <i class="fas fa-home me-2"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection