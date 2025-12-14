<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultController;

Route::get('/', [QuizController::class, 'homeIndex'])->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

Route::post('/signup', [UserController::class, 'register'])->name('signup.register');
Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('isUser')->prefix('/user')->name('user.')->group(function () {
    Route::get('/quiz', [QuizController::class, 'playQuiz'])->name('play_quiz');
    Route::get('/quizzes/all', [QuizController::class, 'showAllQuizzes'])->name('quizzes.all');
    Route::get('/quiz/play/{id}', [QuizController::class, 'playQuizDetail'])->name('quiz.play');
    Route::post('/quiz/submit/{id}', [QuizController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/quiz/result/{id}', [QuizController::class, 'showResult'])->name('quiz.result');
});

Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::prefix('/user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::get('/export', [UserController::class,'exportExcel'])->name('export');
        Route::get('/trash', [UserController::class,'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class,'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [UserController::class,'deletePermanent'])->name('delete_permanent');
    });
});

Route::middleware('isTeacher')->prefix('/teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('dashboard');

    Route::prefix('/quizzes')->name('quizzes.')->group(function () {
        Route::get('/', [QuizController::class, 'index'])->name('index');
        Route::get('/create', [QuizController::class, 'create'])->name('create');
        Route::post('/store', [QuizController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [QuizController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [QuizController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [QuizController::class, 'destroy'])->name('delete');
        Route::get('/export', [QuizController::class,'exportExcel'])->name('export');
        Route::get('/trash', [QuizController::class,'trash'])->name('trash');
        Route::patch('/restore/{id}', [QuizController::class,'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [QuizController::class,'deletePermanent'])->name('delete_permanent');
    });

    Route::prefix('/question')->name('question.')->group(function () {
        Route::get('/', [QuestionController::class,'index'])->name('index');
        Route::get('/create/{quiz_id}', [QuestionController::class,'create'])->name('create');
        Route::post('/store/{quiz_id}', [QuestionController::class,'store'])->name('store');
        Route::get('/edit/{quiz_id}/{id}', [QuestionController::class,'edit'])->name('edit');
        Route::put('/update/{quiz_id}/{id}', [QuestionController::class,'update'])->name('update');
        Route::delete('/delete/{quiz_id}/{id}', [QuestionController::class,'delete'])->name('delete');
        Route::get('/trash', [QuestionController::class,'trash'])->name('trash');
        Route::patch('/restore/{id}', [QuestionController::class,'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [QuestionController::class,'deletePermanent'])->name('delete_permanent');
    });
});

Route::get('/quizzes', [QuizController::class, 'showAllQuizzes'])->name('quizzes.public.all');
Route::get('/quiz/{id}', [QuizController::class, 'showQuizDetail'])->name('quiz.public.show');
Route::get('/play-quiz/{id}', [QuizController::class, 'playQuizDetail'])->name('quiz.play');
Route::post('/submit-quiz/{id}', [QuizController::class, 'submitQuiz'])->name('quiz.submit');
Route::get('/quiz-result/{id}', [QuizController::class, 'showResult'])->name('quiz.result');