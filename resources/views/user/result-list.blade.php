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
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <span>{{ $message }}</span>
        <button type="button" class="close-btn close position-absolute" data-dismiss="alert" aria-label="Close" style="right:10px;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @php
        $user = \Illuminate\Support\Facades\Auth::user();
    @endphp
    @if ($user->is_admin == '3')
    <div class="mb-3">
        <a href="{{ url('/admin') }}" type="button" class="btn btn-primary btn-sm"><i class="bi bi-arrow-left"></i>&nbsp;戻る</a>
    </div>
    @endif


    @foreach($userexams as $u)
    <h5 style="font-weight:bold;">{{ $u->exam_nm }}</h5>
    <div class="exam-block mt-2 mb-5 pl-3 pr-3 clearfix">
        <div class="row pt-3 pb-3 m-0 bd-bottom">
            <div class="col-md-4 col-sm-4 col-4">
                <span style="font-weight: 700;">有効期間</span>
            </div>
            <div class="col-md-8 col-sm-8 col-8">
                {{ $u->start_dt }} ～ {{ $u->end_dt }}
            </div>
        </div>
        <div class="row pt-3 pb-3 m-0 bd-bottom">
            <div class="col-md-4 col-sm-4 col-4">
                <span style="font-weight: 700;">問題数</span>
            </div>
            <div class="col-md-8 col-sm-8 col-8">
                {{ $u->question_count }} 問
            </div>
        </div>
        <div class="row pt-3 pb-3 m-0 bd-bottom">
            <div class="col-md-4 col-sm-4 col-4">
                <span style="font-weight: 700;">試験期間</span>
            </div>
            <div class="col-md-8 col-sm-8 col-8">
                {{ ($u->duration)/60 }} 分
            </div>
        </div>
        <div class="row pt-3 pb-3 m-0 bd-bottom">
            <div class="col-md-4 col-sm-4 col-4">
                <span style="font-weight: 700;">受験状況</span>
            </div>
            <div class="col-md-8 col-sm-8 col-8">
                {{ $u->status }}
            </div>
        </div>
        <div class="row pt-3 pb-3 m-0 bd-bottom text-end col-md-3 float-right" style="font-weight: 700;color:red;">
            <a class="btn btn-primary btn-lg btn-block" href="{{ url('/user/exam-rule/'.$u->exam_id) }}" class="">
                @if( $u->take_exam_status == '1')
                <span>開始</span>
                @elseif( $u->take_exam_status == '2' )
                <span>再開始</span>
                @endif
            </a>
        </div>
    </div>
    @endforeach
    @if( count($userexams) == 0 )
    <div class="exam-block p-1 pt-5 pb-5 d-flex justify-content-center flex-wrap">
        <div class="col-12 text-center mb-3">
            <img class="user-exam-conditions" width="50%" src="{{ asset('assets/img/checklist.gif') }}"> <h3 class="mt-3" style="font-weight: bold;color:#4e73df">試験はありません。</h3>
        </div>
    </div>
    @endif
</div>
@endsection