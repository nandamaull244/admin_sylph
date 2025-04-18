@extends('layout.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Edit user dan image target</h5>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.update', $user['id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Email:</label>
                            <input type="email" name="email" value="{{ $user['email'] }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password (opsional, biarkan kosong jika tidak diubah):</label>
                            <input type="text" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Nama (opsional):</label>
                            <input type="text" name="name" value="{{ $user['name'] }}" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Gambar Lama:</label>
                            <div class="d-flex flex-wrap gap-2" id="existing-images">
                                @foreach ($images as $image)
                                <div class="image-preview"
                                    style="position: relative; display: inline-block; margin: 5px;">
                                    <img src="{{ $image['image_url'] }}"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                                    <button type="button"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 delete-image-btn"
                                        data-id="{{ $image['id'] }}"
                                        data-action="{{ route('images.destroy', $image['id']) }}"
                                        style="padding: 0 5px;">&times;</button>
                                </div>

                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Tambah Gambar Baru:</label>
                            <div id="dropzoneEdit" class="border border-secondary p-3 mb-2 text-center"
                                style="cursor:pointer;">Drop gambar di sini atau klik untuk pilih</div>
                            <input type="file" id="fileInputEdit" name="images[]" multiple class="form-control d-none">
                        </div>

                        <div id="previewContainerEdit" class="d-flex flex-wrap gap-2 mb-3"></div>

                        <button class="btn btn-success">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection