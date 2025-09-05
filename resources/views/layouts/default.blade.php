<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
    <meta charset="utf-8">
</head>

<body id="page-top">
    <div id="wrapper">
        @include('includes.header')
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('includes.nav')
                @yield('content')
            </div>
        </div>
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
    <script>
        // フォーム送信にEnterキーのブロック
        $(document).ready(function() {
            $('form').on('keypress', function(e) {
                var code = e.keyCode || e.which;
                var type = 'localName' in e.target ? e.target.localName : ''
                if (code == 13 && type != 'textarea') {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>