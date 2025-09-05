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
            <form action="{{ url('/admin/category') }}" method="GET" role="form" enctype="multipart/form-data">
                <div class="input-group">
                    <select name="category_type" class="form-control form-control-sm mr-2">
                        <option value="">すべて</option>
                        <option value="1" {{ $category_type == 1 ? 'selected' : '' }}>問題</option>
                        <option value="2" {{ $category_type == 2 ? 'selected' : '' }}>勉強</option>
                        <option value="3" {{ $category_type == 3 ? 'selected' : '' }}>用語</option>
                        <option value="4" {{ $category_type == 4 ? 'selected' : '' }}>試験</option>
                    </select>
                    <button class="btn btn-sm btn-outline-primary" type="submit" style="font-size: 10px;"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
        <div class="col-4 d-flex justify-content-end">
            <a href="{{ url('/admin/category/insert') }}" type="button" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i>&nbsp;新規作成</a>
        </div>
    </div>

    @if(!empty($categories))
    <div class="table-responsive dataTables_Tablet">
        <table class="table table-bordered bg-white" id="dataTable" width="100%" cellspacing="0">
            <colgroup>
                <col class="tb-col1">
                <col>
                <col>
            </colgroup>
            <thead>
                <tr class="text-brown-color text-size-14">
                    <th class="vertical-align-middle" scope="col">#</th>
                    <th class="vertical-align-middle" scope="col">カテゴリ名</th>
                    <th class="vertical-align-middle" scope="col">カテゴリ種類</th>
                    <th class="vertical-align-middle" scope="col">アイコン</th>
                    <th class="vertical-align-middle" scope="col">アクション</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $c)
                <tr class="text-brown-color text-size-14">
                    <td class="vertical-align-middle">{{ $c->no }}</td>
                    <td class="vertical-align-middle"><a href="{{ url('/admin/category/edit/'.$c->category_id) }}" class="">{{ $c->category_nm}}</a></td>
                    @if($c->category_type=="1")
                    <td class="vertical-align-middle">問題</td>
                    @elseif ($c->category_type=="2")
                    <td class="vertical-align-middle">勉強</td>
                    @elseif ($c->category_type=="3")
                    <td class="vertical-align-middle">用語</td>
                    @else
                    <td class="vertical-align-middle">試験</td>
                    @endif
                    <td class="vertical-align-middle">
                        <img src="{{$c->category_icon}}" style="max-width: 10%; height: auto;border-style: solid;">
                    </td>
                    <td class="vertical-align-middle">
                        <a data-toggle="modal" data-id="{{$c->category_id}}" data-target="#myModal{{$c->category_id}}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                        <input type="hidden" name="category_id" value="{{$c->category_id}}">
                        <form action="{{  url('/admin/category/delete/'.$c->category_id) }}" method="POST" role="form" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            {{ method_field('DELETE') }}
                            <div class="modal" id="myModal{{$c->category_id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header" style="border: none;">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="container"></div>
                                        <div class="modal-body" style="text-align:center;">
                                            <p>{{$c->category_nm}}
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
    @if ($categories->hasPages())
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <!-- Previous Page Link -->
                @if ($categories->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $categories->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                @endif

                <!-- Pagination Elements -->
                @foreach ($categories->links()->elements[0] as $page => $url)
                    @if ($page == $categories->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                <!-- Next Page Link -->
                @if ($categories->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $categories->nextPageUrl() }}" rel="next">&raquo;</a></li>
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