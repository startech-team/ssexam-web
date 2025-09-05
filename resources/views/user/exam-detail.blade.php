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

    .form-check-input:checked {
        background-color: #ffffff !important;
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgb(22 110 241 / 25%);
    }

    .exam-block .exam-ques .ques-input-element {
        display: none;
    }

    .exam-block .exam-ques .ques-input {
        border-radius: 5px;
        background: rgba(231,231,231,.2);
    }
    .exam-block .exam-ques .ques-input:hover {
        cursor: pointer;
    }
    .exam-block .exam-ques .ques-input-element:checked + .ques-input {
        color: #fff;
        background: #4e73df;
    }
    .exam-block .exam-ques label {
        display: block;
    }
    .exam-block .user-exam-col {
        display: contents;
    }
</style>
@section('content')
<div class="container user-sp">
    <div id="page-content-wrapper">
        <form action="{{ url('/user/exam-commit') }}" method="POST" id="exam-commit-form">
            {{csrf_field()}}
            <input type="hidden" name="remaing_time" value="{{ $remaing_time }}" id="remaing_time">
            <input type="hidden" name="exam_id" value="{{ $exam->exam_id }}" id="exam_id">
            <input type="hidden" name="ord_no" value="{{ $examform->ord_no }}">
            <input type="hidden" name="pre_ord_no" value="{{ $examform->pre_ord_no }}">
            <input type="hidden" name="nxt_ord_no" value="{{ $examform->nxt_ord_no }}">
            <input type="hidden" name="question_id" value="{{ $examform->question_id }}">
            <input type="hidden" name="question_count" value="{{ $question_count }}">
            <input type="hidden" name="exam_nm" value="{{ $exam->exam_nm }}">
            <div class="container-fluid p-0">
                <div class="card shadow row exam-block ml-0 mr-0 mt-2 mb-3 p-3 clearfix">
                    <div class="d-flex w-100">
                        <div class="col-md-10 fs-6" style="padding-top:0.8rem;">
                            <h5 style="font-weight:bold;">{{ $exam->exam_nm }}</h5>
                        </div>
                        <div class="m-0 bd-bottom text-end col-md-2 text-right px-0">
                            <button type="submit" name="action" value="endBtn" class="btn font-weight-bold btn-warning btn-lg" id="endBtn">終了</button>
                        </div>   
                    </div>
                </div>  
                <div class="card shadow exam-block mt-2 mb-5 clearfix">
                    <div class="card-header row p-3 m-0 bd-bottom">
                        <div class="col-md-4 col-sm-4 col-4 float-left text-primary" style="font-weight:bold;">
                            {{ $examform->ord_no }}/{{ $question_count }} 問
                        </div>
                        <div class="col-md-8 col-sm-8 col-8" style="text-align:end;" style="font-weight:bold;">
                            <h5 style="font-weight: bold;" class="{{ $remaing_time <= 120 ? 'text-danger' : 'text-success' }}"><span id="remaing_time_show">{{ $remaing_time_show }} 分</span></h5>
                        </div>
                    </div>
                    <div class="card-body row p-3 m-0 bd-bottom">
                        <div class="col-12">
                            <p style="overflow-wrap: anywhere; font-weight: bold;">
                                {!! str_replace( ["\n", " "],["</br>", "&nbsp"], $examform->body) !!}
                            </p>
                        </div>
                        <div class="col-12 user-exam-col">
                            <div class="exam-ques col-12 col-lg-8 pb-2">
                                <label>
                                    <input type="radio" name="my_answer" value="1" class="ques-input-element" {{ ($examform->my_answer == '1') ? 'checked' : '' }}/>
                                    <div class="ques-input p-3">  
                                        <div class="ques-info d-flex align-items-center">
                                            <span class="font-weight-bold">1.</span>
                                            <p class="mb-0 pl-4">{!! str_replace( ["\n", " "],["</br>", "&nbsp"], $examform->option1) !!} </p>
                                        </div>                                                      
                                    </div>
                                </label>
                            </div>
                            <div class="exam-ques col-12 col-lg-8 pb-2">
                                <label>
                                    <input type="radio" name="my_answer" value="2" class="ques-input-element" {{ ($examform->my_answer == '2') ? 'checked' : '' }}/>
                                    <div class="ques-input p-3">  
                                        <div class="ques-info d-flex align-items-center">
                                            <span class="font-weight-bold">2.</span>
                                            <p class="mb-0 pl-4">{!! str_replace( ["\n", " "],["</br>", "&nbsp"], $examform->option2) !!} </p>
                                        </div>                                                      
                                    </div>
                                </label>
                            </div>
                        </div>
                        @if( !empty($examform->option3) || !empty($examform->option4) )
                        <div class="col-12 user-exam-col">
                            @if( !empty($examform->option3) )
                            <div class="exam-ques col-12 col-lg-8 pb-2">
                                <label>
                                    <input type="radio" name="my_answer" value="3" class="ques-input-element" {{ ($examform->my_answer == '3') ? 'checked' : '' }}/>
                                    <div class="ques-input p-3">  
                                        <div class="ques-info d-flex align-items-center">
                                            <span class="font-weight-bold">3.</span>
                                            <p class="mb-0 pl-4">{!! str_replace( ["\n", " "],["</br>", "&nbsp"], $examform->option3) !!} </p>
                                        </div>                                                      
                                    </div>
                                </label>
                            </div>
                            @endif
                            @if( !empty($examform->option4) )
                            <div class="exam-ques col-12 col-lg-8 pb-2">
                                <label>
                                    <input type="radio" name="my_answer" value="4" class="ques-input-element" {{ ($examform->my_answer == '4') ? 'checked' : '' }}/>
                                    <div class="ques-input p-3">  
                                        <div class="ques-info d-flex align-items-center">
                                            <span class="font-weight-bold">4.</span>
                                            <p class="mb-0 pl-4">{!! str_replace( ["\n", " "],["</br>", "&nbsp"], $examform->option4) !!}</p>
                                        </div>                                                      
                                    </div>
                                </label>
                            </div> 
                            @endif                               
                        </div>
                        @endif
                    </div>
                    <div>
                        @if( $examform->pre_ord_no != '0' )
                        <div class="row pt-3 pb-3 m-0 bd-bottom text-end col-3 col-md-2 float-left">
                            <button type="submit" name="action" value="preBtn" class="btn btn-primary btn-lg btn-block"><i class="bi bi-arrow-left"></i></button>
                        </div>
                        @endif
                        @if( $examform->nxt_ord_no != '0' )
                        <div class="row pt-3 pb-3 m-0 bd-bottom text-end col-3 col-md-2 float-right">
                            <button type="submit" name="action" value="nxtBtn" class="btn btn-primary btn-lg btn-block"><i class="bi bi-arrow-right"></i></button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    jQuery(function() {
        setInterval(function() {
            var remaing_time = $("#remaing_time").val();
            var exam_id = $("#exam_id").val();
            let route = "{{ url('/user/exam-count-time') }}";
            let token = "{{ csrf_token()}}";
            if (remaing_time == 5) {
                $("#endBtn").click();
            } else {
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        _token: token,
                        remaing_time: remaing_time,
                        exam_id: exam_id,
                    },
                    success: function(response) {
                        $("#remaing_time").val(response);
                        var minCal = response % 60;
                        var min;
                        if(minCal == 0) {
                            min = '00';
                        }
                        else if(minCal == 5) {
                            min = '0' + minCal;
                        }
                        else{
                            min = minCal;
                        }
                        var s = Math.floor(response / 60) + ":" + min;
                        $("#remaing_time_show").text(s + " 分");
                    },
                    error: function(xhr) {
                        alert(response)
                    }
                });
            }
        }, 5000);
    });
</script>
@endsection