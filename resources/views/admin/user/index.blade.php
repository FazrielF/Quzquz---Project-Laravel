@extends('templates.sidebar')

@section('content')
    <div class="container my-5">
        <div class="p-4 rounded shadow-sm" style="background-color:#f7f3f9;">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark">Data User</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.user.export') }}" class="btn" style="background:#234C6A; color:#fff;">Export
                        Excel</a>
                    <a href="{{ route('admin.user.trash') }}" class="btn" style="background:#234C6A; color:#fff;">Sampah</a>
                    <a href="{{ route('admin.user.create') }}" class="btn"
                        style="background:#1B3C53; color:#fff;">Tambah</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Nama</th>
                            <th>Email</th>
                            <th width="15%">Role</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td class="text-center">
                                    @if ($item->role == 'admin')
                                        <span class="badge bg-success">Admin</span>
                                    @elseif ($item->role == 'teacher')
                                        <span class="badge bg-info">Teacher</span>
                                    @elseif ($item->role == 'user')
                                        <span class="badge bg-primary">User</span>
                                    @endif
                                </td>
                                <td class="text-center d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.user.edit', $item->id) }}"
                                        class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('admin.user.delete', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection