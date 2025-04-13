@extends('layout.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Tambah user dan image target</h5>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3">
                                <label>Email:</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Password (opsional, biarkan kosong jika tidak diubah):</label>
                                <input type="text" name="password" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Nama (opsional):</label>
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Tambah Image Target Baru (boleh lebih dari satu):</label>
                                <input type="file" name="image_targets[]" multiple class="form-control">
                            </div>

                            <button class="btn btn-success">Simpan Perubahan</button>
                    </form>

                    <hr>

                    <h5>Image Target Saat Ini</h5>
                    <ul>
                        @foreach($user->imageTargets as $target)
                        <li style="margin-bottom: 10px;">
                            <a href="{{ $target->image_url }}" target="_blank">{{ $target->image_url }}</a>
                            <form action="{{ route('admin.image_targets.destroy', $target->id) }}" method="POST"
                                style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus image target ini?')">Hapus</button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection