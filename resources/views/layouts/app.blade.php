<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.head')
</head>

<body style="background-color: #dbdbdb;">
    <div class="d-flex" id="wrapper">
        <div class="navbar">
            <div class="navbar-inner">
                @guest
                @if (Route::has('login'))
                @endif
                @else
                <ul>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    
                </ul>
                <div class="border-end bg-white" id="sidebar-wrapper">
                    <div class="sidebar-heading border-bottom bg-light" style="background-color: #fff !important;">SS EXAM</div>
                            <div class="list-group list-group-flush" style="background-color: #e9e9e9 !important;">
                            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="{{ url('/admin/dashboard')}}"><i class="bi bi-bar-chart-line"></i>&nbsp;<span></span>ダッシュボード</a>
                            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="{{ url('/admin/account')}}"><i class="bi bi-person-fill"></i>&nbsp;<span></span>アカウント</a>
                            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="{{ url('/admin/group')}}"><i class="bi bi-people"></i>&nbsp;<span></span>グループ</a>
                            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="{{ url('/admin/question')}}"><i class="bi bi-question-circle"></i>&nbsp;<span></span>問題</a>
                            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="{{ url('/admin/exam')}}"><i class="bi bi-chat-dots"></i>&nbsp;<span></span>試験</a>
                            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="{{ url('/admin/study')}}"><i class="bi bi-book-fill"></i>&nbsp;<span></span>勉強</a>
                            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="{{ url('/admin/term')}}"><i class="bi bi-fonts"></i>&nbsp;<span></span>用語</a>
                            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="{{ url('/admin/category')}}"><i class="bi bi-card-list"></i>&nbsp;<span></span>カテゴリ</a>
                         
                        </div>
                        </div>
                </div>
                @endguest
            </div>
        </div>
        @yield('content')
    </div>
</body>

</html>