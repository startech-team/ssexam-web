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
</head>
<body id="page-top" style="background-color: #fff!important;">
    <div id="wrapper" style="background-color: #fff!important;">
        <div id="content-wrapper" style="background-color: #fff!important;">
            @yield('content')
        </div>
    </div>
</body>
</html>