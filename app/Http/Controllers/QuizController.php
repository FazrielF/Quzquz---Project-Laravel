<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{

    public function playQuiz(Request $req)
    {
        if ($req->quiz_id) {
            $quiz = Quiz::with('questions.options')->findOrFail($req->quiz_id);
            return view('user.index', [
                'quiz' => $quiz,
                'questions' => $quiz->questions
            ]);
        }

        $quizzes = Quiz::latest()->get();
        return view('home', [
            'quizzes' => $quizzes
        ]);
    }

    public function showAllQuizzes()
    {
        $quizzes = Quiz::with('user')
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(12);

        return view('quiz', compact('quizzes'));
    }

    public function playQuizDetail($id)
    {
        $quiz = Quiz::with(['questions.options'])
            ->whereNull('deleted_at')
            ->findOrFail($id);

        return view('user.play', compact('quiz'));
    }

    public function submitQuiz(Request $request, $id)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $quiz = Quiz::with([
            'questions.options' => function ($query) {
                $query->where('is_correct', 1);
            }
        ])->findOrFail($id);

        $user = auth()->user();
        $score = 0;
        $totalQuestions = $quiz->questions->count();

        $result = Result::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => 0,
            'started_at' => now()->subMinutes(10),
            'finished_at' => now(),
        ]);

        foreach ($quiz->questions as $question) {
            $userAnswer = $request->answers[$question->id] ?? null;
            $isCorrect = false;

            if ($question->question_type == 'mcq' && $userAnswer) {
                $selectedOption = $question->options->find($userAnswer);
                if ($selectedOption) {
                    $correctOption = $question->options->where('is_correct', 1)->first();
                    if ($correctOption && $correctOption->id == $selectedOption->id) {
                        $isCorrect = true;
                        $score++;
                    }
                }
            }

            Answer::create([
                'result_id' => $result->id,
                'question_id' => $question->id,
                'option_id' => is_numeric($userAnswer) ? $userAnswer : null,
                'answer_text' => !is_numeric($userAnswer) ? $userAnswer : null,
                'is_correct' => $isCorrect,
            ]);
        }

        $percentage = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;
        $result->update([
            'score' => $percentage,
        ]);

        return redirect()->route('user.quiz.result', ['id' => $result->id])
            ->with('success', 'Quiz berhasil disubmit!');
    }

    public function showResult($id)
    {
        $result = Result::with(['quiz', 'answers.question.options'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('result', compact('result'));
    }

    public function showAllQuizzesPublic()
    {
        $quizzes = Quiz::with('user')
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(12);

        return view('quiz', compact('quizzes'));
    }

    public function showQuizDetailPublic($id)
    {
        $quiz = Quiz::with(['questions.options'])
            ->whereNull('deleted_at')
            ->findOrFail($id);

        return view('quiz_detail', compact('quiz'));
    }

    public function homeIndex()
    {
        $quizzes = Quiz::latest()->take(5)->get();
        return view('home', compact('quizzes'));
    }
    public function index()
    {
        $quizzes = Quiz::whereNull('deleted_at')->where('user_id', auth()->id())->latest()->get();
        return view('teacher.quiz.index', compact('quizzes'));
    }

    public function create()
    {
        return view('teacher.quiz.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'required|min:5',
            'time_limit' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('quiz_images', 'public');
        }

        Quiz::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'image' => $image,
        ]);

        return redirect()->route('teacher.quizzes.index')->with('success', 'Quiz berhasil dibuat');
    }

    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        return view('teacher.quiz.edit', compact('quiz'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'required|min:5',
            'time_limit' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $quiz = Quiz::findOrFail($id);

        if ($request->hasFile('image')) {
            // hapus file lama jika ada
            if ($quiz->image) {
                Storage::disk('public')->delete($quiz->image);
            }
            $quiz->image = $request->file('image')->store('quiz_images', 'public');
        }

        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'image' => $quiz->image,
        ]);

        return redirect()->route('teacher.quizzes.index')->with('success', 'Quiz berhasil diperbarui');
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return back()->with('success', 'Quiz dipindahkan ke trash');
    }

    public function trash()
    {
        $quizzes = Quiz::onlyTrashed()->where('user_id', auth()->id())->get();
        return view('teacher.quiz.trash', compact('quizzes'));
    }

    public function restore($id)
    {
        $quiz = Quiz::onlyTrashed()->findOrFail($id);
        $quiz->restore();
        return back()->with('success', 'Quiz berhasil dikembalikan');
    }

    public function deletePermanent($id)
    {
        $quiz = Quiz::onlyTrashed()->findOrFail($id);
        if ($quiz->image) {
            Storage::disk('public')->delete($quiz->image);
        }
        $quiz->results()->forceDelete();

        $questions = Question::where('quiz_id', $quiz->id)->get();
        foreach ($questions as $question) {
            $question->options()->forceDelete();
        }
        $quiz->questions()->forceDelete();

        $quiz->forceDelete();
        return back()->with('success', 'Quiz berhasil dihapus permanen');
    }
}
