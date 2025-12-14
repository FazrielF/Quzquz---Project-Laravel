@extends('templates.nav')

@section('content')
    <div class="quiz-header py-4 text-white" style="background: linear-gradient(135deg, #1B3C53 0%, #234C6A 100%);">
        <div class="container text-center">
            <h1 class="fw-bold mb-2">{{ $quiz->title }}</h1>
            <p class="lead opacity-75 mb-3">{{ $quiz->description }}</p>

            @if($quiz->time_limit)
                <div class="badge bg-white text-dark px-3 py-2 rounded-pill">
                    <i class="fas fa-clock me-2"></i>
                    Waktu: {{ $quiz->time_limit }} menit
                </div>
            @endif
        </div>
    </div>

    <div class="container my-5">
        <form id="quizForm" action="{{ route('user.quiz.submit', $quiz->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Progress: <span
                            id="currentQuestion">1</span>/{{ $quiz->questions->count() }}</span>
                    <span id="progressPercentage" class="fw-bold">0%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div id="progressBar" class="progress-bar bg-info" style="width: 0%"></div>
                </div>
            </div>

            @foreach($quiz->questions as $index => $question)
                <div class="card mb-4 question-{{ $index + 1 }}" style="{{ $index > 0 ? 'display:none;' : '' }}">
                    <div class="card-header text-white" style="background:#456882;">
                        <span class="badge bg-dark me-2">Pertanyaan {{ $index + 1 }}</span>
                        <h5 class="mb-0 mt-2">{{ $question->question_text }}</h5>
                    </div>

                    <div class="card-body">
                        @if($question->question_type == 'mcq')
                            @php $letters = ['A', 'B', 'C', 'D', 'E', 'F']; @endphp
                            @foreach($question->options as $optIndex => $option)
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                        id="q{{ $question->id }}_o{{ $option->id }}" value="{{ $option->id }}"
                                        data-question="{{ $question->id }}">
                                    <label class="form-check-label d-flex align-items-center"
                                        for="q{{ $question->id }}_o{{ $option->id }}">
                                        <span class="badge bg-secondary me-3" style="width: 30px; height: 30px; line-height: 30px;">
                                            {{ $letters[$optIndex] ?? ($optIndex + 1) }}
                                        </span>
                                        {{ $option->option_text }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <textarea name="answers[{{ $question->id }}]" class="form-control" rows="4"
                                placeholder="Tulis jawaban Anda di sini..." data-question="{{ $question->id }}"></textarea>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                <button type="button" class="btn btn-secondary px-4" id="prevBtn" style="display:none;">
                    <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                </button>

                <div>
                    <button type="button" class="btn btn-info px-4" id="nextBtn">
                        Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-success px-4" id="submitBtn" style="display:none;">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Jawaban
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const totalQuestions = {{ $quiz->questions->count() }};
            let currentQuestion = 1;
            const answeredQuestions = new Set();

            function updateProgress() {
                const progress = (answeredQuestions.size / totalQuestions) * 100;
                document.getElementById('progressBar').style.width = `${progress}%`;
                document.getElementById('progressPercentage').textContent = `${Math.round(progress)}%`;
                document.getElementById('currentQuestion').textContent = currentQuestion;

                document.getElementById('prevBtn').style.display = currentQuestion > 1 ? 'flex' : 'none';
                document.getElementById('nextBtn').style.display = currentQuestion < totalQuestions ? 'inline-block' : 'none';
                document.getElementById('submitBtn').style.display = currentQuestion === totalQuestions ? 'inline-block' : 'none';
            }

            function showQuestion(num) {
                document.querySelectorAll('.question-card').forEach(card => {
                    card.style.display = 'none';
                });
                document.querySelector(`.question-${num}`).style.display = 'block';
                currentQuestion = num;
                updateProgress();
            }

            function checkQuestionAnswered(questionId) {
                const inputs = document.querySelectorAll(`[name="answers[${questionId}]"]`);
                for (let input of inputs) {
                    if (input.type === 'radio' && input.checked) return true;
                    if (input.type === 'textarea' && input.value.trim() !== '') return true;
                }
                return false;
            }

            document.getElementById('nextBtn').addEventListener('click', function () {
                const currentQuestionId = document.querySelector(`.question-${currentQuestion}`)
                    .querySelector('[data-question]')?.dataset.question;

                if (currentQuestionId && checkQuestionAnswered(currentQuestionId)) {
                    answeredQuestions.add(currentQuestionId);
                }

                if (currentQuestion < totalQuestions) {
                    showQuestion(currentQuestion + 1);
                }
            });

            document.getElementById('prevBtn').addEventListener('click', function () {
                if (currentQuestion > 1) {
                    showQuestion(currentQuestion - 1);
                }
            });

            document.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('change', function () {
                    const questionId = this.dataset.question ||
                        this.name.match(/\[(\d+)\]/)?.[1];

                    if (questionId && checkQuestionAnswered(questionId)) {
                        answeredQuestions.add(questionId);
                        updateProgress();
                    }
                });
            });

            updateProgress();
        });
    </script>
@endpush