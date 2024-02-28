 <!-- Brand Logo -->
 <a href="index3.html" class="brand-link">
    <span class="brand-text font-weight-light">Perpustakaan Digital</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      {{-- <div class="image">
        <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div> --}}
      <div class="info">
        <a href="#" class="d-block" onclick="showPasswordModal(event)">{{ Auth::user()->username }}</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    {{-- <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div> --}}

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->

       <li class="nav-header">Menu</li>
        @can('view home')
        <li class="nav-item">
          <a href="{{ route('home.index') }}" class="nav-link ">
            <i class="nav-icon far fa-book"></i>
            <p>
              Home
            </p>
          </a>
        </li>
        @endcan
        <li class="nav-header">Menu Utama</li>

        @can('manage buku')
        <li class="nav-item">
          <a href="{{ route('buku.index') }}" class="nav-link ">
            <i class="nav-icon far fa-book"></i>
            <p>
              Buku
            </p>
          </a>
        </li>
        @endcan
        @can('manage kategori')
        <li class="nav-item">
          <a href="{{ route('kategori.index') }}" class="nav-link ">
            <i class="nav-icon far fa-book"></i>
            <p>
              Kategori
            </p>
          </a>
        </li>
        @endcan
      
        @can('add peminjaman')
        <li class="nav-item">
          <a href="{{ route('peminjaman.index') }}" class="nav-link ">
            <i class="nav-icon far fa-book"></i>
            <p>
              Peminjaman
            </p>
          </a>
        </li>
        @endcan
      
        @can('manage ulasan')
        <li class="nav-item">
          <a href="{{ route('ulasan.index') }}" class="nav-link ">
            <i class="nav-icon far fa-book"></i>
            <p>
              Ulasan
            </p>
          </a>
        </li>
        @endcan
    
        @can('manage koleksi')
        <li class="nav-item">
          <a href="{{ route('koleksi.index') }}" class="nav-link ">
            <i class="nav-icon far fa-book"></i>
            <p>
              Koleksi
            </p>
          </a>
        </li>
        @endcan

     
         
        @can('manage user')
        <li class="nav-header">Menu Akun</li>
        <li class="nav-item">
          <a href="{{ route('admin-user.index') }}" class="nav-link ">
            <i class="nav-icon far fa-book"></i>
            <p>
              Akun User
            </p>
          </a>
        </li>
        @endcan
         
        @can('manage permissions')
        <li class="nav-item">
          <a href="{{ route('role-pengguna.index') }}" class="nav-link ">
            <i class="nav-icon far fa-book"></i>
            <p>
              Roles
            </p>
          </a>
        </li>
        @endcan
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->