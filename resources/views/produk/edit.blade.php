@extends('layout.app')
@section('title', 'Edit Produk')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('produk.update', $produk['id']) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-5 text-center">
                        <label for="image" class="form-label">Preview image</label>
                        <div class="border rounded p-2 mb-3">
                            <img id="preview-image-edit" src="#" alt="Preview image" class="img-fluid rounded" style="max-height: 300px; object-fit: cover; display: none;">
                        </div>
                        <img src="{{ $produk['image_url'] ?? '#' }}" alt="Current Image" class="img-fluid rounded mb-3" style="max-height: 300px;">
                        <input type="file" class="form-control" name="image_edit" id="image_edit" accept="image/png, image/jpeg, image/jpg, image/webp">
                        <small class="form-text text-muted">Hanya file PNG, JPG, JPEG, dan WEBP yang diizinkan.</small>
                    </div>

                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label">Nama produk</label>
                            <input type="text" class="form-control" name="product_name" value="{{ $produk['nama'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control" name="price" value="{{ $produk['harga'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link produk</label>
                            <input type="text" class="form-control" name="link" value="{{ $produk['link'] }}" required>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">Update Data</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
document.getElementById('image_edit').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewImage = document.getElementById('preview-image-edit');

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewImage.style.display = 'none';
    }
});
</script>
@endpush