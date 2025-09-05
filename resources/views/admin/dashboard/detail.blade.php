@extends('layouts.default')

@section('content')
<div class="container-fluid">

    @if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif

    <div class="row">
        <!-- First Column -->
        <div class="col-12">
            <a href="{{ url('/admin/dashboard/pdf/'.$acc_id.'/'.$exam_id)}}" class="btn btn-sm btn-danger mb-2" target="_blank"><i class="bi bi-file-earmark-pdf"></i>&nbsp;PDF</a>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-12 d-flex flex-wrap w-100 justify-content-between">
                            <div class="dash-detail-ttl text-left">
                                <h6 class="m-0 font-weight-bold text-primary text-size-14">{{ $exam_nm }}</h6>
                            </div>
                            <div class="text-right pl-0 text-size-14">{{ $correct_answer_count }} / {{ $question_count }}</div>
                        </div>
                        <hr style="width: 98%;">
                        <div class="col-12 d-flex align-items-center justify-content-between">
                            <div class="col-6 text-left mg-leftright">
                                <h6 class="m-0 text-size-14">{{ $name }}</h6>
                            </div>
                            <div class="col-6 text-right px-0">
                                <span class="btn btn-sm {{ $result == '合格' ? 'font-weight-bold btn-success' : 'font-weight-bold btn-danger' }}">{{ $result }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($details as $d)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary text-size-14">問{{ $loop->index +1 }}</h6>
                </div>
                <div class="card-body p-4">
                    <p style="overflow-wrap: anywhere;" class="mb-4 font-weight-bold text-size-14">
                        {!! str_replace( ["\n", " "],["</br>", "&nbsp"],  $d->body) !!}
                    </p>
                    @if( $d->my_answer == null or $d->my_answer == '' )
                    <span class="text-danger mb-2 text-size-14">※回答していません。</span>
                    @endif
                    <div class="detail-bg input-group pb-3 mt-3">
                        @if( '1' == $d->correct_answer and $d->correct_answer == $d->my_answer )
                        <div class="form-check col-lg-8 p-3 correct-answer text-size-14">
                        @elseif( '1' == $d->correct_answer and $d->correct_answer != $d->my_answer)
                        <div class="form-check col-lg-8 p-3 correct-answer text-size-14">
                        @elseif( '1' != $d->correct_answer and '1' == $d->my_answer )
                        <div class="form-check col-lg-8 p-3 wrong-answer text-size-14">
                        @else
                        <div class="form-check col-lg-8 p-3 text-size-14">
                        @endif
                            <label class="form-check-label d-flex align-items-center">
                                <span>1.&nbsp;&nbsp;</span>
                                <p class="mb-0 pl-2"> {!! str_replace( ["\n"],["</br>"],  $d->option1) !!}</p>
                            </label>
                        </div>
                    </div>
                    <div class="detail-bg input-group pb-3">
                        @if( '2' == $d->correct_answer and $d->correct_answer == $d->my_answer )
                        <div class="form-check col-lg-8 p-3 correct-answer text-size-14">
                        @elseif( '2' == $d->correct_answer and $d->correct_answer != $d->my_answer)
                        <div class="form-check col-lg-8 p-3 correct-answer text-size-14">
                        @elseif( '2' != $d->correct_answer and '2' == $d->my_answer )
                        <div class="form-check col-lg-8 p-3 wrong-answer text-size-14">
                        @else
                        <div class="form-check col-lg-8 p-3 text-size-14">
                        @endif
                            <label class="form-check-label d-flex align-items-center">
                                <span>2.&nbsp;&nbsp;</span>
                                <p class="mb-0 pl-2"> {!! str_replace( ["\n"],["</br>"],  $d->option2) !!}</p>
                            </label>
                        </div>
                    </div>
                    <div class="detail-bg input-group pb-3">
                        @if( '3' == $d->correct_answer and $d->correct_answer == $d->my_answer )
                        <div class="form-check col-lg-8 p-3 correct-answer text-size-14">
                        @elseif( '3' == $d->correct_answer and $d->correct_answer != $d->my_answer)
                        <div class="form-check col-lg-8 p-3 correct-answer text-size-14">
                        @elseif( '3' != $d->correct_answer and '3' == $d->my_answer )
                        <div class="form-check col-lg-8 p-3 wrong-answer text-size-14">
                        @else
                        <div class="form-check col-lg-8 p-3 text-size-14">
                        @endif
                            <label class="form-check-label d-flex align-items-center">
                                <span>3.&nbsp;&nbsp;</span>
                                <p class="mb-0 pl-2"> {!! str_replace( ["\n"],["</br>"],  $d->option3) !!}</p>
                            </label>
                        </div>
                    </div>
                    <div class="detail-bg input-group pb-3">
                        @if( '4' == $d->correct_answer and $d->correct_answer == $d->my_answer )
                        <div class="form-check col-lg-8 p-3 correct-answer text-size-14">
                        @elseif( '4' == $d->correct_answer and $d->correct_answer != $d->my_answer)
                        <div class="form-check col-lg-8 p-3 correct-answer text-size-14">
                        @elseif( '4' != $d->correct_answer and '4' == $d->my_answer )
                        <div class="form-check col-lg-8 p-3 wrong-answer text-size-14">
                        @else
                        <div class="form-check col-lg-8 p-3 text-size-14">
                        @endif
                            <label class="form-check-label d-flex align-items-center">
                                <span>4.&nbsp;&nbsp;</span>
                                <p class="mb-0 pl-2"> {!! str_replace( ["\n"],["</br>"],  $d->option4) !!}</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>   
@endsection