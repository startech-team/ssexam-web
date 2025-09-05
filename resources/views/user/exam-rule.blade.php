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
    <div class="exam-block p-5 d-flex justify-content-center flex-wrap">
        <div class="col-12 text-center mb-3">
            <h5 style="font-weight: bold;color:#4e73df">注意：</h5>
            <br>試験を開始した後は終了まで受ける必要があります。
        </div>
        <div class="col-12 pt-3 pb-3 m-0 bd-bottom col-md-3">
            <a class="btn btn-primary btn-lg btn-block" href="{{ url('/user/exam-detail/'.$exam_id) }}" class="">開始</a>
        </div>
    </div>

</div>
@endsection