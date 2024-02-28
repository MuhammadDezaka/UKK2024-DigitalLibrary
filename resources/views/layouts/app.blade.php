<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Digital Library</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/plugins') }}/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('assets/plugins') }}/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('assets/plugins') }}/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  {{-- <link rel="stylesheet" href="{{ asset('assets/plugins') }}/jqvmap/jqvmap.min.css"> --}}
  <link rel="stylesheet" href="{{ asset('assets/plugins') }}/sweetalert2/sweetalert2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist') }}/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('assets/plugins') }}/overlayScrollbars/css/OverlayScrollbars.min.css">


  @stack('stylesheets')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  {{-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div> --}}

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      

    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <form action="{{ route('logout') }}" method="post">
          @csrf
          <button type="submit" class="btn btn-info"> Logout</button>
        </form>
      </li>
    </ul>

   
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" >
    @include('partials.sidebar')
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" >
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
       <h3>@yield('title')</h3> 
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" >
      <div class="container-fluid">
        @yield('main')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->


    <div id="update-pw" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <form action="{{ route('admin-user.change-password') }}" method="POST"
                  onsubmit="updatePassword(this, event)" enctype="multipart/form-data">
                  @csrf
                  @method('PATCH')
                  <div class="modal-header">
                      <h5 class="modal-title" id="myModalLabel">Update Password</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      {{-- <input type="hidden" name="id"> --}}
                      <input type="hidden" name="_method">
                      <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                      <div class="mb-3">
                          <label class="form-label" for="password"><strong>Password</strong></label>
                          <input type="password" name="password" id="password" class="form-control" required>
                      </div>
                      <div class="mb-3">
                          <label class="form-label" for="password_confirmation"><strong>Confirm
                                  Password</strong></label>
                          <input type="password" name="password_confirmation" class="form-control"
                              id="password_confirmation" required>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary waves-effect"
                          data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-success waves-effect waves-light">Simpan
                          Data</button>
                  </div>
              </form>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">Digital Library</a>.</strong>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins') }}/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/plugins') }}/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins') }}/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/plugins') }}/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- overlayScrollbars -->
<script src="{{ asset('assets/plugins') }}/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist') }}/js/adminlte.js"></script>

<script src="{{ asset('assets/plugins') }}/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('assets/dist') }}/js/demo.js"></script> --}}
<script src="{{ asset('assets/dist') }}/js/custom.js"></script>
<script src="{{ asset('assets/plugins') }}/axios/axios.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('assets/dist') }}/js/pages/dashboard.js"></script>
<script>
  const passwordElement = document.querySelector('#update-pw');
  const passwordBootstrap = new bootstrap.Modal(passwordElement);
  const $passwordElement = $(passwordElement);

  const showPasswordModal = (event) => {
      event.preventDefault();

      $passwordElement.find('.modal-title').html('Reset Password');
      $passwordElement.find('[name="id"]').val({{ Auth::user()->id }});
      $passwordElement.find('[name="password"]').text('').val('');
      $passwordElement.find('[name="password_confirmation"]').text('').val('');

      passwordBootstrap.show();
  };

  const updatePassword = (form, event) => {
      event.preventDefault();
      $form = $(form);

      $form.find('[type="submit"]')
          .addClass('disabled')
          .attr('disabled', 'disabled')
          .html('Sedang Mengirim Data');
      let data = new FormData();

      data.append('_token', $form.find('[name="_token"]').val());
      data.append('_method', $form.find('[name="_method"]').val());
      data.append('id', $form.find('[name="id"]').val());
      data.append('password', $form.find('[name="password"]').val());
      data.append('password_confirmation', $form.find('[name="password_confirmation"]').val());
      axios({
              url: $form.attr('action'),
              method: 'POST',
              data: data
          })
          .then(responseJson => {
              Swal.fire('Berhasil', 'Data Berhasil Diubah', 'success');
              passwordBootstrap.hide();
          })
          .catch(errorResponse => {
              console.log(errorResponse)
              handleErrorRequest(errorResponse);
          })
          .then(() => {
              $form.find('[type="submit"]')
                  .removeClass('disabled')
                  .removeAttr('disabled')
                  .html('Simpan');
          });
  }
</script>
@stack('scripts')
</body>
</html>
