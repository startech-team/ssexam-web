<!DOCTYPE html>
<html lang="en">
    <head>
        @include('includes.head')
        <meta charset="utf-8">
        <style>
            body {
                background-image: url('/assets/img/error-bg.jpg');
                background-repeat: no-repeat;
                background-attachment: fixed;
            }
            .error-box p {
                font-weight: bold;
                font-size: 18px;
                color: red;
            }
            .error-box a {
                align-self: center;
            }
        </style>
    </head>
    <body>
        <div class="container">     
            <div class="d-flex align-items-center justify-content-center vh-100">
                <div class="card shadow pt-3 pb-3 text-center error-box">
                    <div class="mb-4">
                        <img width="50%" src="{{ asset('assets/img/rabbit.png') }}">
                    </div>
                    
                    <p>申し訳ございません。<br/> エラーが発生しています。</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">ログインへ</a>
                </div>
            </div>   
        </div>
    </body>
</html>