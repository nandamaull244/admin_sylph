@extends('layout.app')
@section('title','Daftar artwork')

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
            <h5 class="card-title fw-semibold mb-4">Daftar Artwork</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <input type="text" id="searchBar" class="form-control" placeholder="Cari akun...">
                    </div>
                </div>
            </div>
            <table class="table table-bordered" id="artwork-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Judul</th>
                        <th>Image</th>
                        <th>Video</th>
                        <th>Mind</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
   
</div>
@endsection
