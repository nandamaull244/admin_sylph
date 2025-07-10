@extends('layout.app')
@section('title','Harga produk')

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
            <h5 class="card-title fw-semibold mb-4">Harga Produk</h5>
                       <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <input type="text" id="searchBar" class="form-control" placeholder="Cari akun...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <a href="{{ route('produk.create') }}" class="btn btn-primary float-end">Tambah Produk</a>
                    </div>
                </div>
            </div>
            <table class="table table-bordered" id="produk-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>link</th>
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
    function deleteProduk(id) {
        Swal.fire({
            title: 'Yakin hapus produk?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/produk/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        $('#produk-table').DataTable().ajax.reload();
                        Swal.fire('Berhasil!', 'Produk telah dihapus.', 'success');
                    } else {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                    }
                });
            }
        });
    }
    $(function () {
    @if (request()->routeIs('produk.index'))
    Swal.fire({
      title: 'Memuat data...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
    @endif

    let table = $('#produk-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url('/produk') }}',
            complete: function () {
                Swal.close(); // Tutup swal setelah data selesai dimuat
            },
            error: function () {
                Swal.fire('Gagal!', 'Tidak dapat mengambil data produk.', 'error');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama', name: 'nama' },
            { data: 'harga', name: 'harga' },
            { data: 'link', name: 'link' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        language: {
            emptyTable: "Tidak ada data produk tersedia."
        }
    });
});


    $(document).ready(function() {
      $('#searchBar').on('keyup', function() {
        $('#produk-table').DataTable().search(this.value).draw();
      });
    });
  </script>
  
@endpush
