@extends('layouts.default')
@section('content')
<div class="container-fluid mb-5">
    <div class="d-flex align-items-center mb-2">
        <a href="{{ url('admin') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
    </div>
    <div class="card shadow">
        <div class="card-header">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">試験一覧</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
            @if(!empty($exams))
                <div class="table-responsive dataTables_Tablet">
                    <table class="table table-bordered bg-white" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-brown-color text-size-14">
                                <th scope="col">#</th>
                                <th scope="col">試験名</th>
                                <th scope="col">結果</th>
                                <th scope="col">有効期限</th>
                                <th scope="col">期間</th>
                                <th scope="col">問題数</th>
                                <th scope="col">合格率</th>
                                <th scope="col">対象者数</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exams as $data)
                            <tr class="text-brown-color text-size-14">
                                <td>{{ $data->no }}</td>
                                <td>
                                    <a href="{{ url('admin/exam/summary', $data->exam_id) }}">{{ $data->exam_nm}}</a>
                                </td>
                                <td class="text-left">合格：{{ $data->result->passed_count ?? 0 }} / 不合格：<span class="{{ $data->result->failed_count ?? 0 > 0 ? 'text-danger' : '' }}">{{ $data->result->failed_count ?? 0 }}</span></td>
                                <td class="text-left">{{ $data->start_dt }} ～ {{ $data->end_dt }}</td>
                                <td class="text-right">{{ $data->duration }}分</td>
                                <td class="text-right">{{ $data->ques_count }}問</td>
                                <td class="text-right">{{ $data->win_rate }}%</td>
                                <td class="text-right">{{ $data->acc_count }}人</td>
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
        </div>
    </div>
</div>
@endsection