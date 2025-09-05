@guest
@if (Route::has('login'))
@endif
@else
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <a class="sidebar-brand d-flex align-items-center justify-content-cente user-nav" href="{{ url('/user')}}">
        <div style="width:35px;height:35px;background:#4e73df;">    
            <div class="sidebar-brand-icon rotate-n-15">
                <i class='fas fa-star-half-alt' style="color:#ffffff;font-size:2rem;"></i>
            </div>
        </div>
        <div class="fs-5 fw-bold sidebar-brand-text mx-3">SS EXAM&nbsp;&nbsp;</div>
    </a>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('assets/img/user.png') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                 <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changepassword">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    パスワード変更
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>{{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>

    </ul>

</nav>
<!-- Modal -->
<div class="modal fade" id="changepassword" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <span>{{ $message }}</span>
                <button type="button" class="close-btn close position-absolute" data-dismiss="alert" aria-label="Close" style="right:10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changePassword" style="color:#4e73df;font-weight: bold;">パスワード変更</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <form action="{{ url('/admin/changePasswordOk') }}" method="POST">
          {{csrf_field()}}
          <div class="modal-body">
            <label for="username1" class="form-label">新規パスワード</label>
            <input type="password" class="form-control mb-3" name="new_password">
            @if ($errors->has('new_password'))
            <div>
                <span class="text-danger">{{ $errors->first('new_password') }}</span>
            </div>
            @endif
                <label for="username1" class="form-label">再確認パスワード</label>
                <input type="password" class="form-control" name="confirm_password">
            @if ($errors->has('confirm_password'))
                 <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
            @endif
            @if ($errors->has('ERGP0013'))
                <span class="text-danger">{{ $errors->first('ERGP0013') }}</span>
            @endif
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">変更</button>
          </form>
          </div>
        </div>
    </div>
  </div>
<script>
    @if (count($errors) > 0 && (($errors->has('new_password')) || ($errors->has('confirm_password')) || ($errors->has('ERGP0013'))))
        $('#changepassword').modal('show');
    @endif
</script>
@endguest