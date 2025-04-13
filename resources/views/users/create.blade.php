@extends('layout.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Tambah user dan image target</h5>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
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
                                    <input type="file" name="images[]" multiple required class="form-control" onchange="previewFiles(this.files)">
                                </div>
                                <ul id="file-list"></ul>
                                <button class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('script')
<script>
        function previewFiles(files){
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = ''; //clear list
        for(let i = 0; < files.length; i++){
            let li = document.createElement('li');
            li.textContent = files[i].name;
            fileList.appendChild(li);
        }
    }
</script>
@endpush