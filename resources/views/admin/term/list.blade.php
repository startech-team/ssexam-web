@extends('layouts.default')

@section('content')
<div class="container-fluid examTable_List">

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

    <div class="row mb-2">
        <div class="col-8 d-flex justify-content-start">
            <form action="{{ url('/admin/term') }}" method="GET" role="form" enctype="multipart/form-data">
                <div class="input-group">
                    <select name="category_id" class="form-control form-control-sm mr-2">
                        <option value="">すべて</option>
                        @foreach($categoryTypeList as $c)
                            <option value="{{ $c->category_id }}" {{ $category_id == $c->category_id ? 'selected' : '' }}>{{ $c->category_nm }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-outline-primary" type="submit" style="font-size: 10px;"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
        <div class="col-4 d-flex justify-content-end">
            <a href="{{ url('/admin/term/insert') }}" type="button" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i>&nbsp;新規作成</a>
        </div>
    </div>

    @if(!empty($terms))
    <div class="table-responsive dataTables_Tablet">
        <table class="table table-bordered bg-white" id="dataTable" width="100%" cellspacing="0">
            <colgroup>
                <col class="tb-col1">
                <col>
                <col>
            </colgroup>
            <thead>
                <tr class="text-brown-color text-size-14">
                    <th scope="col">#</th>
                    <th scope="col">用語カテゴリー名</th>
                    <th scope="col">用語の言葉</th>
                    <th scope="col">用語の説明</th>
                    <th scope="col">アクション</th>
                </tr>
            </thead>
            <tbody>
                @foreach($terms as $t)
                <tr class="text-brown-color text-size-14">
                    <td>{{ $loop->index +1 }}</td>
                    <td><a href="{{ url('/admin/term/edit/'.$t->term_id) }}" class="">{{$t->category_nm}}</a></td>
                    <!-- @if($t->category_id=="1")
                    <td> 問題</td>
                    @elseif ($t->category_id=="2")
                    <td> 勉強</td>
                    @else
                    <td> 用語</td>
                    @endif -->
                    <td> {{$t->word}}</td>
                    <td> {{$t->explanation}}</td>
                    <td>
                        <a data-toggle="modal" data-id="{{$t->term_id}}" data-target="#myModal{{$t->term_id}}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                        <input type="hidden" name="term_id" value="{{$t->category_id}}">
                        <form  action="{{  url('/admin/term/delete/'.$t->term_id) }}" method="POST" role="form" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <div class="modal" id="myModal{{$t->term_id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header" style="border: none;">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="container"></div>
                                        <div class="modal-body" style="text-align:center;">
                                            <p>{{$t->word}}
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
    @if ($terms->hasPages())
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <!-- Previous Page Link -->
                @if ($terms->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $terms->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                @endif

                <!-- Pagination Elements -->
                @foreach ($terms->links()->elements[0] as $page => $url)
                    @if ($page == $terms->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                <!-- Next Page Link -->
                @if ($terms->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $terms->nextPageUrl() }}" rel="next">&raquo;</a></li>
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