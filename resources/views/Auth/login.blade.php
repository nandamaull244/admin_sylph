<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sylph - Login</title>
    <link rel="shortcut icon" type="image/png"
        href="{{ asset('assets') }}/images/logos/logo.svg" />
    <link rel="stylesheet" href="{{ asset('assets') }}/css/styles.min.css" />

</head>

<body>
    @if($errors->any())
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            color: '#950101'
          });
        });
      </script>
    @endif
    <!--  Body Wrapper -->
    <div class="page-wrapper-login" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('assets') }}/images/logos/logo-app.png"
                                        width="120" alt="">
                                </a>
                                <h5 class="text-center">Sylph.art Admin</h4>
                                    <form action="{{ url('/login-attempt') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Email Admin</label>
                                            <input type="email" class="form-control" name="email" id="name"
                                                aria-describedby="emailHelp" required placeholder="isi email admin">
                                        </div>
                                        <div class="mb-4">
                                            <label for="exampleInputPassword1" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password"
                                                    id="password" required placeholder="isi password">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="togglePassword" tabindex="-1">
                                                    <iconify-icon icon="mdi:eye-off" id="togglePasswordIcon">
                                                    </iconify-icon>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- <a href="./index.html" class="btn btn-primary w-100 py-8 fs-4 mb-4">Log in</a> --}}
                                        <button type="submit" class="btn btn-primary w-100 py-8 fs-4">Log in</button>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets') }}/libs/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('assets') }}/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('#togglePassword').on('click', function () {
                const passwordInput = $('#password');
                const icon = $('#togglePasswordIcon');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                icon.attr('icon', type === 'password' ? 'mdi:eye-off' : 'mdi:eye');
            });
        });

    </script>
</body>

</html>
