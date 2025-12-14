@extends('templates.sidebar')

@section('content')
<div class="container my-5">
    <div class="p-4 rounded shadow-sm" style="background:#f7f3f9;">
        <h4 class="fw-bold mb-4 text-dark">Edit User</h4>

        <form action="{{ route('admin.user.update',$user->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Isi ulang password">
            </div>

            <button class="btn" style="background:#3b142a; color:#fff;">Update</button>
            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
