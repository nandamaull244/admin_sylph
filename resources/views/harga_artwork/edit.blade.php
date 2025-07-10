@extends('layout.app')

@section('title', 'Edit Harga Artwork')

@section('content')
<div class="container-fluid">
    <div class="card">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Edit Harga Artwork</h5>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('harga_artwork.update', $hargaArtwork['id']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label>Harga:</label>
                            <input type="number" name="price" value="{{ old('price', $hargaArtwork['price']) }}" required class="form-control">
                        </div>
                        <button class="btn btn-success">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
