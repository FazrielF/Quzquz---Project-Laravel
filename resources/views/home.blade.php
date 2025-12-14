@extends('templates.nav')

@section('content')
<div class="hero d-flex flex-column justify-content-center align-items-center text-white"
     style="background: linear-gradient(135deg, #1B3C53 0%, #234C6A 100%); height: 100vh;">
    <h1 class="mb-3 fw-bold display-4">Quzquz</h1>
    <p class="mb-4 lead">Cari dan mainkan kuis dengan cepat</p>
    <a href="#listKuis" class="btn btn-lg text-white" style="background:#456882;">Cari Kuis</a>
</div>

<div id="listKuis" class="py-5 text-white" style="background:#234C6A;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Kuis Populer</h3>
            <a href="{{ route('user.quizzes.all') }}" class="btn btn-light btn-sm rounded-pill">Lihat Semua</a>
        </div>

        <div class="scrollable-kuis overflow-auto pb-3">
            <div class="d-flex flex-nowrap gap-3">
                @foreach($quizzes as $quiz)
                <div class="card" style="width: 18rem; min-width: 18rem; background:#456882;">
                    <div class="card-body text-white">
                        <h5 class="card-title fw-bold">{{ $quiz->title }}</h5>
                        <p class="card-text">{{ Str::limit($quiz->description, 60) }}</p>
                        @auth
                            <a href="{{ route('user.quiz.play', $quiz->id) }}" 
                               class="btn btn-light btn-sm rounded-pill">Mulai</a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="btn btn-light btn-sm rounded-pill">Login untuk Mulai</a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="footer text-center py-5" 
     style="background: linear-gradient(135deg, #E3E3E3 0%, #1B3C53 100%);">
    <p class="mb-2 fw-medium" style="color: #234C6A;">support@quzquz.com</p>
    <p class="mb-0" style="color: #456882; font-size: 0.9rem;">© 2025 Quzquz</p>
</div>

<style>
.scrollable-kuis::-webkit-scrollbar {
    height: 8px;
}
.scrollable-kuis::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
    border-radius: 4px;
}
.scrollable-kuis::-webkit-scrollbar-thumb {
    background: #456882;
    border-radius: 4px;
}
</style>
@endsection