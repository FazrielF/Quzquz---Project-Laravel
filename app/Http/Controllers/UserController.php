<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:5',
        ]);

        $createData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);
        if ($createData) {
            return redirect()->route('login') -> with('success', 'Register berhasil, silahkan login');
        } else {
            return redirect()->route('signup.register')->with('error', 'Error, silahkan coba lagi');
        }
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $data = $request->only(['email', 'password']);
        if (Auth::attempt($data)) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai admin');
            } elseif (Auth::user()->role === 'teacher') {
                return redirect()->route('teacher.dashboard')->with('success', 'Berhasil login sebagai teacher');
            } else {
                return redirect()->route('home')->with('success', 'Berhasil login');
            }
        } else {
            return back()->with('error', 'Gagal login. Periksa email dan password Anda.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home')->with('logout','Anda sudah logout! silahkan login kembali untuk akses lengkap');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy('role')->get();
        return view('admin.user.index', compact('user'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email'=> 'required',
            'password'=> 'required|min:5',
        ], [
            'name.required'=> 'Nama wajwib diisi',
            'email.required'=> 'Email harus diisi',
            'password.required'=> 'Password wajib diisi',
            'password.min'=> 'Password minimal 5 karakter',
        ]);
        $createData = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'role' => 'teacher',
        ]);
        if ($createData) {
            return redirect()->route('admin.user.index')->with('success','Berhasil tambah data User!');
        } else {
            return redirect()->back()->with('error','Gagal silahkan coba lagi!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:5',
        ], [
            'name.required'=> 'Nama User harus diisi',
            'email.required'=> 'Email harus diisi',
            'password.required'=> 'Password harus diisi',
            'password.min'=> 'Minimal password 5 karakter',
        ]);

        $updateData = User::where('id', $id)->update([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        if ($updateData) {
            return redirect()->route('admin.user.index')->with('success','Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error','gagal! silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        foreach ($user->quizzes as $quiz) {
            foreach ($quiz->questions as $question) {
                $question->options()->delete();
            }
            $quiz->questions()->delete();
            $quiz->results()->delete();
            $quiz->delete();
        }

        $user->delete();
        return redirect()->route('admin.user.index')->with('success','Berhasil hapus data!');
    }

    public function exportExcel()
    {
        $fileName = 'data-pengguna.xlsx';
        return Excel::download(new UserExport(), $fileName);
    }

    public function trash()
    {
        $userTrash = User::onlyTrashed()->get();
        return view('admin.user.trash', compact('userTrash'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.user.index')->with('success','Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        foreach ($user->quizzes()->onlyTrashed()->get() as $quiz) {
            $quiz->results()->forceDelete();
            foreach ($quiz->questions()->onlyTrashed()->get() as $question) {
                $question->options()->forceDelete();
            }
            $quiz->questions()->forceDelete();
            $quiz->forceDelete();
        }

        $user->forceDelete();
        return redirect()->back()->with('success','Berhasil menghapus data seutuhnya!');
    }

    public function chart()
    {
        // Get user registration data for the last 30 days
        $usersPerDay = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        // Fill in missing dates with 0
        $startDate = now()->subDays(29)->startOfDay();
        for ($i = 0; $i < 30; $i++) {
            $currentDate = $startDate->copy()->addDays($i)->format('Y-m-d');
            $labels[] = $startDate->copy()->addDays($i)->format('d/m');

            $count = $usersPerDay->where('date', $currentDate)->first();
            $data[] = $count ? $count->count : 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
