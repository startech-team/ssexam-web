<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
    <meta charset="utf-8">
    <style>
        nav .user-nav {
            display: flex !important;
        }
    </style>
    <script src="{{ asset('assets/js/user.js') }}"></script>
    <title>{{ $title }}</title>
</head>

<body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" style="height: 100vh;">
            @include('includes.nav-user')
            @yield('content')
        </div>
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
</body>

</html>