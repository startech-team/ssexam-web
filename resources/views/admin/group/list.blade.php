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
    <div class="mb-2 d-flex justify-content-end">
        <a href="{{ url('/admin/group/insert') }}" type="button" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i>&nbsp;新規作成</a>
    </div>

    @if(!empty($groups))
    <div class="table-responsive dataTables_Tablet">
        <!-- Table -->
        <table class="table table-bordered bg-white" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr class="text-brown-color text-size-14">
                    <th scope="col">#</th>
                    <th scope="col">グループ名</th>
                    <th scope="col">メンバー数</th>
                    <th scope="col">削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $g)
                <tr class="text-brown-color text-size-14">
                    <td>
                        <span class="align-middle">{{ $g->no }}</span>
                    </td>
                    <td>
                        <a class="align-middle" href="{{ url('/admin/group/edit/'.$g->group_id) }}">{{ $g->group_name}}</a>
                    </td>
                    <td class="text-right">
                        <span class="align-middle">{{ $g->acc_count }}人</td>
                    <td>
                        <a data-toggle="modal" data-id="{{$g->group_id}}" data-target="#myModal{{$g->group_id}}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                        <input type="hidden" name="group_id" value="{{$g->group_id}}">
                        <form id="myform" action="{{  url('/admin/group/delete/'.$g->group_id) }}" method="POST" role="form" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            {{ method_field('PATCH') }}
                            <div class="modal" id="myModal{{$g->group_id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header" style="border: none;">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="container"></div>
                                        <div class="modal-body" style="text-align:center;">
                                            <p>{{$g->group_name}}
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
        @if ($groups->hasPages())
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">

                <!-- Previous Page Link -->
                @if ($groups->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $groups->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                @endif

                <!-- Pagination Elements -->
                @foreach ($groups->links()->elements[0] as $page => $url)
                    @if ($page == $groups->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                <!-- Next Page Link -->
                @if ($groups->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $groups->nextPageUrl() }}" rel="next">&raquo;</a></li>
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