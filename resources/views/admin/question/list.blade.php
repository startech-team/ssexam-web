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
            <form action="{{ url('/admin/question') }}" method="GET" role="form" enctype="multipart/form-data">
                <div class="input-group">
                    <select name="question_type" class="form-control form-control-sm mr-2">
                        <option value="">すべて</option>
                        @foreach($categoryList as $c)
                            <option value="{{ $c->category_id }}" {{ $question_type == $c->category_id ? 'selected' : '' }}>{{ $c->category_nm }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="title_detail" class="form-control form-control-sm mr-2" value="{{ $title_detail }}" placeholder="タイトル・内容">
                    <button class="btn btn-sm btn-outline-primary" type="submit" style="font-size: 10px;"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
        <div class="col-4 d-flex justify-content-end">
            <a href="{{ url('/admin/question/insert') }}" type="button" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i>&nbsp;新規作成</a>
        </div>
    </div>
    @endif

    @if(!empty($questions))
    <div class="table-responsive dataTables_Tablet">
        <table class="table table-bordered bg-white" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr class="text-brown-color text-size-14">
                    <th style="text-align: center;width: 5%;">#</th>
                    <th style="width: 20%;">問題種類</th>
                    <th style="width: 20%;">問題タイトル</th>
                    <th style="width: 45%;">問題内容</th>
                    <th style="width: 10%;">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $data)
                <tr class="text-brown-color text-size-14">
                    <td style="text-align: center;">{{ $data->no}}</td>
                    <td> <a href="{{ url('/admin/question/edit/'.$data->question_id) }}">{{ $data->category_nm}}</a></td>
                    <td> {{ $data->title}}</td>
                    <td> {{ $data->body}}</td>
                    <td>
                        <a data-toggle="modal" data-id="{{$data->question_id}}" data-target="#delete{{$data->question_id}}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                        <form action="{{ url('/admin/question/delete/'.$data->question_id) }}" method="POST" role="form" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal" id="delete{{$data->question_id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header" style="border: none;">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="container"></div>
                                        <div class="modal-body" style="text-align:center;">
                                            <p>{{$data->title}}
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
        @if ($questions->hasPages())
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <!-- Previous Page Link -->
                    @if ($questions->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $questions->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                    @endif

                    <!-- Pagination Elements -->
                    @foreach ($questions->links()->elements[0] as $page => $url)
                        @if ($page == $questions->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    <!-- Next Page Link -->
                    @if ($questions->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $questions->nextPageUrl() }}" rel="next">&raquo;</a></li>
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