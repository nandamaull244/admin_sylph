@extends('layout.app')
@section('title','Harga Artwork')

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
            <h5 class="card-title fw-semibold mb-4">Harga Artwork</h5>
            <table class="table table-bordered" id="price-table">
                <thead>
                    <tr>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
   
</div>
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function () {
    @if (request()->routeIs('harga_artwork.index'))
    Swal.fire({
      title: 'Memuat data...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
    @endif

    let table = $('#price-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('harga_artwork.index') }}',
            complete: function () {
                Swal.close(); // Tutup swal setelah data selesai dimuat
            },
            error: function () {
                Swal.fire('Gagal!', 'Tidak dapat mengambil data artwork.', 'error');
            }
        },
        columns: [
            { data: 'price', name: 'price' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            emptyTable: "Tidak ada data harga artwork tersedia."
        }
    });
});
    // Initialize DataTable index
    $(document).ready(function() {
      $('#searchBar').on('keyup', function() {
        $('#price-table').DataTable().search(this.value).draw();
      });
    });
  </script>
@endpush