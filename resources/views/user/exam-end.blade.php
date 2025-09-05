@extends('layouts.user')
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
<div class="container user-sp">
    <div class="card shadow exam-block p-3 pt-5 d-flex justify-content-center flex-wrap">
        <div class="col-12 text-center mb-3">
            <h1 class="tit_complete" style="font-weight: bold;color:#4e73df">お疲れ様でした。</h1>
            <p>試験の結果は担当者から連絡します。<br>
                ありがとうございました。
            </p>
            <br>
            <div class="completeImg">
                <img class="user-exam-conditions" width="50%" src="{{ asset('assets/img/rabbit.png') }}">
            </div>
            <a href="{{ url('/user') }}" class="btn btn-primary btn-md mt-4">ホームへ</a>
        </div>
    </div>
</div>
@endsection