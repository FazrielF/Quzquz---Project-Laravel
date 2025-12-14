@extends('templates.nav')

@section('content')
<div class="quiz-header py-5 text-white" 
     style="background: linear-gradient(135deg, #1B3C53 0%, #234C6A 100%);">
    <div class="container text-center">
        <h1 class="fw-bold mb-3 display-5">Semua Kuis Tersedia</h1>
        <p class="lead opacity-75">Temukan dan mainkan berbagai kuis menarik yang dibuat oleh para pengajar</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">
        @forelse($quizzes as $quiz)
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-lg" style="background:#456882;">
                @if($quiz->image)
                    <img src="{{ asset('storage/' . $quiz->image) }}" 
                         class="card-img-top" 
                         alt="{{ $quiz->title }}"
                         style="height: 180px; object-fit: cover;">
                @else
                    <div class="bg-dark bg-gradient d-flex align-items-center justify-content-center" 
                         style="height: 180px;">
                        <i class="fas fa-question-circle fa-4x text-white-50"></i>
                    </div>
                @endif
                
                <div class="card-body text-white">
                    <h5 class="card-title fw-bold mb-2">{{ $quiz->title }}</h5>
                    <p class="card-text small opacity-75 mb-3">{{ Str::limit($quiz->description, 80) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 small opacity-75">
                        <div>
                            <i class="fas fa-user me-1"></i>
                            {{ $quiz->user->name ?? 'Unknown' }}
                        </div>
                        @if($quiz->time_limit)
                        <div>
                            <i class="fas fa-clock me-1"></i>
                            {{ $quiz->time_limit }}m
                        </div>
                        @endif
                    </div>
                    
                    @auth
                        <a href="{{ route('user.quiz.play', $quiz->id) }}" 
                           class="btn btn-light btn-sm w-100 rounded-pill">
                            <i class="fas fa-play-circle me-2"></i> Mainkan
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="btn btn-light btn-sm w-100 rounded-pill">
                            <i class="fas fa-sign-in-alt me-2"></i> Login untuk Main
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-question-circle fa-4x text-muted mb-4"></i>
            <h3 class="text-muted">Belum Ada Kuis Tersedia</h3>
            <p class="text-muted">Silahkan kembali nanti atau buat kuis Anda sendiri</p>
        </div>
        @endforelse
    </div>
    
    @if($quizzes->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $quizzes->links() }}
    </div>
    @endif
</div>
@endsection