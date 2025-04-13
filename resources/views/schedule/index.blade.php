@extends('layout.app')
@section('title','Daftar Guru')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Daftar Guru</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <input type="text" id="searchBar" class="form-control" placeholder="Cari Guru...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                            Tambah Guru
                        </button>
                    </div>
                </div>

                @include('schedule.modal_add_schedule')
            </div>
            <table id="teacherTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama guru</th>
                        <th>Mata pelajaran</th>
                        <th>Kelas</th>
                        <th>Hari mengajar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Nanda Maulana</th>
                        <th>Bahasa Arab</th>
                        <th>1 Awaliyah</th>
                        <th>Malam selasa</th>
                        <th>
                            <a href="#" class="btn btn-primary btn-sm">Edit</a>
                            <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                        </th>
                        
                    </tr>
                    {{-- @foreach($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->phone }}</td>
                            <td>{{ $teacher->address }}</td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- @push('scripts') --}}
    {{-- <script>
        $(document).ready(function() {
            $('#teacherTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('teachers.data') }}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address', name: 'address' }
                ]
            });
        });
    </script> --}}
</div>
@endsection