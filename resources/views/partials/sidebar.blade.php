<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="./index.html" class="text-nowrap logo-img">
                <img src="{{ asset ('assets') }}/images/logos/logo.svg" alt="" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">Beranda</span>
                </li> 
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ url('/') }}" aria-expanded="false">
                        <span>
                            <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li> 
                <li class="nav-small-cap">
                  <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4" class="fs-6">
                  </iconify-icon>
                  <span class="hide-menu">AKUN CUSTOMER</span>
              </li>
              <li class="sidebar-item">
                  <a class="sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
                      <span>
                          <iconify-icon icon="solar:user-bold-duotone" class="fs-6"></iconify-icon>
                      </span>
                      <span class="hide-menu">Tambah akun</span>
                  </a>
              </li>
              <li class="sidebar-item">
                  <a class="sidebar-link" href="{{ route('artwork.index') }}" aria-expanded="false">
                      <span>
                        <iconify-icon icon="solar:clapperboard-play-bold-duotone" class="fs-6"></iconify-icon>
                      </span>
                      <span class="hide-menu">Lihat artwork</span>
                  </a>
              </li>
              <li class="sidebar-item">
                  <a class="sidebar-link" href="https://hiukim.github.io/mind-ar-js-doc/tools/compile" target="_blank" aria-expanded="false">
                      <span>
                          <iconify-icon icon="solar:document-bold" class="fs-6"></iconify-icon>
                      </span>
                      <span class="hide-menu">Compile .mind</span>
                  </a>
              </li>
              {{-- <li class="sidebar-item">
                  <a class="sidebar-link" href="./sample-page.html" aria-expanded="false">
                      <span>
                          <iconify-icon icon="solar:album-bold" class="fs-6"></iconify-icon>
                      </span>
                      <span class="hide-menu">Tambah image target</span>
                  </a>
              </li> --}}
                
                
                
            </ul>
            
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>