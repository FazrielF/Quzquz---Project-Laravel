@extends('templates.sidebar')

@section('content')
<div class="container my-5">
    <div class="p-4 rounded shadow-sm" style="background:#f7f3f9;">
        <h4 class="fw-bold mb-4 text-dark">Data Sampah User</h4>
        <a href="{{ route('admin.user.index') }}" class="btn mb-3" style="background:#456882; color:#fff;">Kembali</a>
        <table class="table table-bordered bg-white">
            <thead class="table-light text-center">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th width="25%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userTrash as $key => $item)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td class="text-center d-flex justify-content-center gap-2">
                        <form action="{{ route('admin.user.restore', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="fas fa-undo me-1"></i>Kembalikan
                                </button>
                            </form>
                            <form action="{{ route('admin.user.delete_permanent', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>Hapus Permanen
                                </button>
                            </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
