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
@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function () {
    @if (request()->routeIs('artwork.index'))
    Swal.fire({
      title: 'Memuat data...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
    @endif

    let table = $('#artwork-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('artwork.index') }}',
            complete: function () {
                Swal.close(); // Tutup swal setelah data selesai dimuat
            },
            error: function () {
                Swal.fire('Gagal!', 'Tidak dapat mengambil data artwork.', 'error');
            }
        },
        columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user.email', name: 'user.email' },
                { data: 'title', name: 'title' },
                { data: 'image_url', name: 'image_url', orderable: false, searchable: false },
                { data: 'video_url', name: 'video_url', orderable: false, searchable: false },
                { data: 'mind_files', name: 'mind_files', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        language: {
            emptyTable: "Tidak ada data artwork tersedia."
        }
    });
});
    // Initialize DataTable index
    $(document).ready(function() {
      $('#searchBar').on('keyup', function() {
        $('#artwork-table').DataTable().search(this.value).draw();
      });
    });
  </script>
  @endpush
