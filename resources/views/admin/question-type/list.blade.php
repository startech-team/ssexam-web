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
    <div class="mb-3">
        <a href="{{ url('/admin/questionType/insert') }}" type="button" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i>&nbsp;新規作成</a>
    </div>
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
                    <th scope="col">問題種類</th>
                    <th scope="col">削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questionTypes as $q)
                <tr class="text-brown-color text-size-14">
                    <td>{{ $loop->index +1 }}</td>
                    <td><a href="{{ url('/admin/questionType/edit/'.$q->question_type_id) }}" class="">{{ $q->question_type_nm}}</a></td>
                    <td>
                        <a data-toggle="modal" data-id="{{$q->question_type_id}}" data-target="#myModal{{$q->question_type_id}}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                        <input type="hidden" name="group_id" value="{{$q->question_type_id}}">
                        <form id="myform" action="{{  url('/admin/questionType/delete/'.$q->question_type_id) }}" method="POST" role="form" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal" id="myModal{{ $q->question_type_id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header" style="border: none;">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="container"></div>
                                        <div class="modal-body" style="text-align:center;">
                                            <p>{{ $q->question_type_nm}}
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
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });

    $(document).ready(function() {
        $("#dataTable_length").hide();
        $("#dataTable_info").hide();
    });
</script>
@endsection