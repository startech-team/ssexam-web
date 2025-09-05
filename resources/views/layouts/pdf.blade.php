<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: migmix;
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/migmix-2p-regular.ttf')}}") format('truetype');
        }

        @font-face {
            font-family: migmix;
            font-style: bold;
            font-weight: bold;
            src: url("{{ storage_path('fonts/migmix-2p-bold.ttf')}}") format('truetype');
        }
    </style>
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        @yield('content')
    </div>
</body>

</html>