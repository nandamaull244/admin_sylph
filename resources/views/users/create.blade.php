@extends('layout.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="container-fluid">
    <div class="card">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Tambah user dan image target</h5>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Email:</label>
                            <input type="email" name="email" required class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Password:</label>
                            <input type="text" name="password" required class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Nama (opsional):</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Upload Image Target (boleh lebih dari 1):</label>
                            <div id="dropzone" class="border border-secondary p-3 mb-2 text-center"
                                style="cursor:pointer;">Drop gambar di sini atau klik untuk pilih</div>
                            <input type="file" id="fileInput" name="images[]" multiple required class="form-control d-none">
                        </div>
                        <div id="previewContainer" class="d-flex flex-wrap gap-2 mb-3"></div>
                    
                        <div class="mb-3">
                            <label>Upload File .mind (urutan tidak harus sesuai tetapi nama harus sesuai!!)</label>
                            <div id="mindDropzone" class="border border-secondary p-3 mb-2 text-center" style="cursor:pointer;">
                                Drop file .mind di sini atau klik untuk pilih
                            </div>
                            <input type="file" id="mindFileInput" name="minds[]" multiple required class="form-control d-none">
                        </div>

                        <div id="mindPreviewContainer" class="d-flex flex-wrap gap-2 mb-3"></div>
                        <div class="alert alert-info">
                            File <strong>.mind</strong> harus merupakan hasil kompilasi dari gambar yang di-upload.
                            <br>Contoh: upload <code>ikan.jpg</code> & <code>ikan.jpg.mind</code>
                        </div>

                        <button class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
