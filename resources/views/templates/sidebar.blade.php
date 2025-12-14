<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quzquz</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex">
        <div class="bg-dark text-light p-3 position-sticky" style="top:0; width:250px; height:100vh; overflow-y:auto;">
            <div class="mb-4 text-center">
                <img src="{{ asset('storage/asset/Quzquz.png') }}" alt="Quzquz" class="img-fluid rounded-circle"
                    width="120" height="120">
                <p class="mt-2 mb-0 fw-bold">{{ Auth::user()->name }}</p>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-light">
                        <i class="fas fa-chart-line me-2"></i>Dashboard
                    </a>
                </li>

                @if(Auth::user()->role == 'admin')
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.quizzes.index') }}" class="nav-link text-light">
                            <i class="fas fa-clipboard-list me-2"></i>Data Quiz
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.user.index') }}" class="nav-link text-light">
                            <i class="fas fa-user-group me-2"></i>Data Pengguna
                        </a>
                    </li>
                @endif

                @if(Auth::user()->role == 'teacher')
                    <li class="nav-item mb-2">
                        <a href="{{ route('teacher.quizzes.index') }}" class="nav-link text-light">
                            <i class="fas fa-book-open me-2"></i>Data Kuis
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('teacher.question.index') }}" class="nav-link text-light">
                            <i class="fas fa-question-circle me-2"></i>Bank Soal
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-light">
                            <i class="fas fa-list-check me-2"></i>Results
                        </a>
                    </li>
                @endif
                <li class="nav-item mt-4">
                    <a href="{{ route('logout') }}" class="btn btn-danger w-100">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('script')
</body>

</html>