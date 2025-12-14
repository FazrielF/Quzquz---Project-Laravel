<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $req)
    {
        $quizzes = Quiz::where('user_id', auth()->id())->get();

        $question = [];
        if ($req->quiz_id) {
            $question = Question::where('quiz_id', $req->quiz_id)->get();
        }

        return view('teacher.question.index', compact('quizzes', 'question'));
    }

    public function create($quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        return view('teacher.question.create', compact('quiz'));
    }

    public function store(Request $req, $quiz_id)
    {
        foreach ($req->questions as $q) {

            $question = Question::create([
                'quiz_id' => $quiz_id,
                'question_text' => $q['question_text'],
                'question_type' => $q['question_type'],
                'time_limit' => $q['time_limit']
            ]);

            if ($q['question_type'] == 'mcq') {
                foreach ($q['options'] as $option) {
                    $opt = Option::create([
                        'question_id' => $question->id,
                        'option_text' => $option
                    ]);

                    if (strtoupper($q['correct_answer']) == strtoupper($option[0])) {
                        Answer::create([
                            'question_id' => $question->id,
                            'correct_option_id' => $opt->id
                        ]);
                    }
                }
            }
        }
        return redirect()->route('teacher.question.index', ['quiz_id' => $quiz_id])
            ->with('success', 'Soal berhasil ditambahkan');
    }

    public function edit($quiz_id, $id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $question = Question::with('options')->findOrFail($id);

        $options = $question->options;
        $correct = $options->firstWhere('is_correct', 1);
        $correct_answer = $correct ? $correct->id : null;

        return view('teacher.question.edit', compact('quiz', 'question', 'options', 'correct_answer'));
    }

    public function update(Request $request, $quiz_id, $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:mcq,essay',
            'time_limit' => 'nullable|integer|min:1',
            'options' => 'array',
            'options.*' => 'nullable|string',
            'correct_option' => 'nullable'
        ]);

        $question = Question::findOrFail($id);

        $question->update([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'time_limit' => $request->time_limit,
        ]);

        if ($request->question_type === 'mcq') {
            $question->options()->delete();

            if ($request->has('options')) {
                foreach ($request->options as $index => $optText) {
                    $isCorrect = false;
                    if ($request->filled('correct_option') && (string) $request->correct_option === (string) $index) {
                        $isCorrect = true;
                    }
                    $question->options()->create([
                        'option_text' => $optText,
                        'is_correct' => $isCorrect ? 1 : 0,
                    ]);
                }
            }
        } else {
            $question->options()->delete();
        }

        return redirect()->route('teacher.question.index', ['quiz_id' => $quiz_id])
            ->with('success', 'Soal berhasil diperbarui');
    }

    public function trash(Request $req)
    {
        $quizzes = Quiz::where('user_id', auth()->id())->get();

        $trashQuestions = collect();
        if ($req->quiz_id) {
            $trashQuestions = Question::onlyTrashed()
                ->where('quiz_id', $req->quiz_id)
                ->get();
        }

        return view('teacher.question.trash', compact('quizzes', 'trashQuestions'));
    }

    public function delete($quiz_id, $id)
    {
        Question::findOrFail($id)->delete();
        return back()->with('success', 'Soal dipindahkan ke Trash');
    }

    public function restore($id)
    {
        Question::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Soal berhasil dikembalikan');
    }


    public function deletePermanent($id)
    {
        $question = Question::onlyTrashed()->findOrFail($id);
        $question->options()->forceDelete();
        $question->forceDelete();
        return back()->with('success', 'Soal dihapus permanen');
    }
}
