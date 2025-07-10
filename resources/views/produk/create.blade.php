@extends('layout.app')

@section('title', 'Tambah Produk')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Form Tambah Promosi</h5>
        </div>
        <div class="card-body">
            <form id="promotionForm" method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row">
                    <!-- Preview Gambar -->
                    <div class="col-md-5 text-center">
                        <label for="image" class="form-label">Preview image</label>
                        <div class="border rounded p-2 mb-3">
                            <img id="preview-image" src="#" alt="Preview image" class="img-fluid rounded" style="max-height: 300px; object-fit: cover; display: none;">
                        </div>
                        <input type="file" class="form-control" id="image" name="image"
                            accept="image/png, image/jpeg, image/jpg, image/webp" required>
                        <small class="form-text text-muted">Hanya file PNG, JPG, JPEG, dan WEBP yang diizinkan.</small>
                    </div>

                    <!-- Input Detail -->
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Nama produk</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link produk(shopee)</label>
                            <input type="text" class="form-control" id="link" name="link" required>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">Simpan Data</button>
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
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewImage = document.getElementById('preview-image');

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