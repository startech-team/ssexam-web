@extends('layouts.mail')
<style>
    .exam-block {
        background: #ffffff;
        border: 2px solid #d0d1d5;
        border-radius: 10px;
        font-size: 0.95rem;
    }

    .bd-bottom {
        border-bottom: 1px solid #d0d1d5;
    }

    .bd-bottom:last-child {
        border: none;
    }

    .exam-block a {
        color: #ffffff;
    }
</style>
@section('content')
<div class="container">
    <div class="row exam-block p-2 pt-5 pb-5">
        <div class="col-12 mb-3">
            <p class="text-dark" style="text-decoration: none;">
                {{ $name }} 様<br><br>

                SS EXAMのご利用ありがとうございます。<br>

                パスワードをリセットしました。<br>
                下記の内容をご利用ください。<br><br>
                URL : https://elearning-star.com<br>
                パスワード：{{ $password }}
                <br><br>
                以上、よろしくお願いいたします。
            </p>
        </div>
    </div>
</div>
@endsection