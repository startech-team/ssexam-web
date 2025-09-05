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
    <div class="row exam-block p-5 d-flex justify-content-center">
        <div class="col-12 text-center mb-3">
            <p>
                {{ $data->name }} さん<br><br>

                SS EXAMのご利用ありがとうございます。

                パスワードをリセットしました。<br>
                下記の内容をご利用ください。<br><br>
                URL：http://wwww.3-s-s-s.co.jp<br>
                パスワード：{{ $data->password }}
                <br><br>
            </p>
            <br>
            <img class="user-exam-conditions" width="50%" src="{{ asset('assets/img/rabbit.png') }}">
        </div>
    </div>
</div>
@endsection