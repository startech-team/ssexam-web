@extends('layouts.default')

@section('content')
<div class="container-fluid">
    @php
        $user = \Illuminate\Support\Facades\Auth::user();
    @endphp
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <span>{{ $message }}</span>
        <button type="button" class="close-btn close d-flex justify-content-end" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if ($message = Session::get('error'))
    <div class="alert alert-danger">
        <span>{{ $message }}</span>
        <button type="button" class="close-btn close d-flex justify-content-end" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if ($user->is_admin == '1')
    <div class="row mb-2">
        <div class="col-8 d-flex justify-content-start">
            <form action="{{ url('/admin/exam') }}" method="GET" role="form" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="text" name="exam_nm" class="form-control form-control-sm mr-2" value="{{ $exam_nm }}" placeholder="試験名">
                    <button class="btn btn-sm btn-outline-primary" type="submit" style="font-size: 10px;"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
        <div class="col-4 d-flex justify-content-end">
        <a href="{{ url('/admin/exam/insert') }}" type="button" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i>&nbsp;新規作成</a>
        </div>
    </div>
    @endif

    @if(!empty($exams))
    <div class="table-responsive dataTables_Tablet">
        <table class="table table-bordered bg-white" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr class="text-brown-color text-size-14">
                    <th width="2%" scope="col">#</th>
                    <th width="20%" scope="col">試験名</th>
                    <th width="10%" scope="col">種類</th>
                    <th width="10%" scope="col">有効期限</th>
                    <th width="5%" scope="col">期間</th>
                    <th width="5%" scope="col">問題数</th>
                    <th width="5%" scope="col">合格率</th>
                    <th width="5%" scope="col">対象者数</th>
                    <th width="15%" scope="col">結果</th>
                    <th width="5%" scope="col">削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exams as $e)
                <tr class="text-brown-color text-size-14">
                    <td width="2%">{{ $loop->index +1 }}</td>
                    <td width="20%">
                        <a href="{{ url('/admin/exam/edit/'.$e->exam_id) }}">{{ $e->exam_nm}}</a>
                    </td>
                     <td width="10%">{{ $e->exam_type }}</td>
                    <td width="10%">{{ $e->start_dt }} ～ {{ $e->end_dt }}</td>
                    <td class="text-right" width="5%">{{ $e->duration }}分</td>
                    <td class="text-right" width="5%">{{ $e->ques_count }}問</td>
                    <td class="text-right" width="5%">{{ $e->win_rate }}%</td>
                    <td class="text-right" width="5%">{{ $e->acc_count }}人</td>
                    <td width="15%">合格：{{ $e->passed_count ?? 0 }} / 不合格：<span class="{{ $e->failed_count ?? 0 > 0 ? 'text-danger' : '' }}">{{ $e->failed_count ?? 0 }}</span></td>
                    <td width="5%">
                        <a data-toggle="modal" data-id="{{$e->exam_id}}" data-target="#myModal{{$e->exam_id}}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                        <input type="hidden" name="group_id" value="{{$e->exam_id}}">
                        <form id="myform" action="{{  url('/admin/exam/delete/'.$e->exam_id) }}" method="POST" role="form" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal" id="myModal{{ $e->exam_id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header" style="border: none;">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="container"></div>
                                        <div class="modal-body" style="text-align:center;">
                                            <p>{{ $e->exam_nm}}
                                                を削除します。よろしいでしょうか？
                                            </p>
                                        </div>
                                        <div class="modal-footer" style="border: none;">
                                            <button type="submit" class="btn btn-primary">OK</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($exams->hasPages())
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <!-- Previous Page Link -->
                    @if ($exams->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $exams->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                    @endif

                    <!-- Pagination Elements -->
                    @foreach ($exams->links()->elements[0] as $page => $url)
                        @if ($page == $exams->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    <!-- Next Page Link -->
                    @if ($exams->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $exams->nextPageUrl() }}" rel="next">&raquo;</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                    @endif
                </ul>
            </nav>
        @endif

    </div>
    @endif

</div>
@endsection