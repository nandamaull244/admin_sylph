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
<script>
  document.addEventListener('DOMContentLoaded', function () {
      function setupImageUploader({ inputId, dropzoneId, previewContainerId }) {
          const input = document.getElementById(inputId);
          const dropzone = document.getElementById(dropzoneId);
          const previewContainer = document.getElementById(previewContainerId);
          let dt = new DataTransfer();

          dropzone.addEventListener('click', () => input.click());

          ['dragenter', 'dragover'].forEach(eventName => {
              dropzone.addEventListener(eventName, e => {
                  e.preventDefault();
                  dropzone.classList.add('bg-light');
              });
          });

          ['dragleave', 'drop'].forEach(eventName => {
              dropzone.addEventListener(eventName, e => {
                  e.preventDefault();
                  dropzone.classList.remove('bg-light');
              });
          });

          dropzone.addEventListener('drop', e => {
              const files = e.dataTransfer.files;
              addFilesToPreview(files);
          });

          input.addEventListener('change', function () {
              addFilesToPreview(this.files);
          });

          function addFilesToPreview(files) {
              previewContainer.innerHTML = '';
              dt = new DataTransfer();

              Array.from(files).forEach(file => {
                  dt.items.add(file);
                  const reader = new FileReader();
                  reader.onload = function (e) {
                      const wrapper = document.createElement('div');
                      wrapper.className = 'image-preview';
                      wrapper.style.position = 'relative';
                      wrapper.style.display = 'inline-block';
                      wrapper.style.margin = '5px';

                      const img = document.createElement('img');
                      img.src = e.target.result;
                      img.style.width = '100px';
                      img.style.height = '100px';
                      img.style.objectFit = 'cover';
                      img.style.borderRadius = '8px';

                      const close = document.createElement('span');
                      close.textContent = '×';
                      close.style.position = 'absolute';
                      close.style.top = '0';
                      close.style.right = '5px';
                      close.style.background = 'rgba(0,0,0,0.6)';
                      close.style.color = '#fff';
                      close.style.padding = '2px 6px';
                      close.style.cursor = 'pointer';
                      close.style.borderRadius = '0 8px 0 8px';

                      close.onclick = function () {
                          const newFiles = Array.from(dt.files).filter(f => f !== file);
                          dt = new DataTransfer();
                          newFiles.forEach(f => dt.items.add(f));
                          input.files = dt.files;
                          addFilesToPreview(dt.files);
                      };

                      wrapper.appendChild(img);
                      wrapper.appendChild(close);
                      previewContainer.appendChild(wrapper);
                  };
                  reader.readAsDataURL(file);
              });

              input.files = dt.files;
          }
      }

      // Inisialisasi uploader untuk create
      if (document.getElementById('fileInput')) {
          setupImageUploader({
              inputId: 'fileInput',
              dropzoneId: 'dropzone',
              previewContainerId: 'previewContainer'
          });
      }

      // Inisialisasi uploader untuk edit
      if (document.getElementById('fileInputEdit')) {
          setupImageUploader({
              inputId: 'fileInputEdit',
              dropzoneId: 'dropzoneEdit',
              previewContainerId: 'previewContainerEdit'
          });
      }
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Fungsi hapus gambar lama
    document.querySelectorAll('.delete-image-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const action = this.dataset.action;
            if (!confirm('Yakin ingin menghapus gambar ini?')) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;

            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrf;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        });
    });
});
</script>



  @stack('scripts')