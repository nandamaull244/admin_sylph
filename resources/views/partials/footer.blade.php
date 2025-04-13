<div class="py-6 px-6 text-center">
  <p class="mb-0 fs-4">Developed by <a href="https://adminmart.com/" target="_blank"
      class="pe-1 text-primary text-decoration-underline">Trident Startup</a>Supported by <a href="https://themewagon.com/" target="_blank"
      class="pe-1 text-primary text-decoration-underline">CV Agenzy Creative</a></p>
</div>
<script src="{{ asset ('assets') }}/libs/jquery/dist/jquery.min.js"></script>
  <script src="{{ asset ('assets') }}/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset ('assets') }}/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="{{ asset ('assets') }}/libs/simplebar/dist/simplebar.js"></script>
  <script src="{{ asset ('assets') }}/js/sidebarmenu.js"></script>
  <script src="{{ asset ('assets') }}/js/app.min.js"></script>
  <script src="{{ asset ('assets') }}/js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <!-- Include jQuery and DataTables JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

  <script>
    // Initialize DataTable index
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: '{{ route('users.index') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'image_url', name: 'image_url', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });

    $(document).ready(function() {
      $('#searchBar').on('keyup', function() {
        $('#users-table').DataTable().search(this.value).draw();
      });
    });
  </script>

  @stack('scripts')