<!doctype html>
<html lang="en">

@include('partials.header')

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    @include('partials.sidebar')
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
     @include('partials.navbar')
      <!--  Header End -->
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </div>
  @include('partials.footer')
</body>

</html>