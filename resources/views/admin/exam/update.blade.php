@extends('layouts.default')

@section('content')
<div class="container-fluid">

    @if ($status == 'not-edit')
    <div class="alert alert-warning">
        <span class="text-danger" style="font-weight: bold;">試験が開始したため試験情報は修正不可です。対象者は追加可能です。</span>
    </div>
    @endif
    <!-- Trigger Button for Popup -->
    <!-- Button to trigger modal -->
    <!-- 再テスト Modal -->
    <div id="modalDialog" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
            <form action="{{ url('admin/reexam') }}" method="POST">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title">再テスト</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4 mt-2 d-flex justify-content-center">
                        <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                            <label for="updateExamNm" class="form-label">試験名</label>
                            <div class="input-group">
                                <input type="hidden" name="examID" value="{{ old('examID', $examID) }}" />
                                <input type="hidden" name="winRate" value="{{ old('winRate', $winRate) }}" />
                                <input type="hidden" name="duration" value="{{ old('duration', $duration) }}" />
                                <input type="hidden" name="examType" value="{{ old('examType', $examType) }}" />
                                <input id="updateExamNm" type="text" name="updateExamNm" value="{{ old('updateExamNm', $updateExamNm) }}"
                                    class="form-control" id="updateExamNm" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 mt-2 d-flex justify-content-center">
                        <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                            <label for="updateStartDate" class="form-label">有効期限開始</label>
                            <div class="input-group date" id="date-picker3">
                                <input type="text" name="updateStartDate" class="form-control" id="updateStartDate"
                                    value="{{ old('updateStartDate', $updateStartDate) }}" autocomplete="off" required />
                                <span class="input-group-append">
                                    <span class="input-group-text bg-light d-block">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 mt-2 d-flex justify-content-center">
                        <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                            <label for="updateEndDate" class="form-label">有効期限終了</label>
                            <div class="input-group date" id="date-picker4">
                                <input type="text" name="updateEndDate" class="form-control" id="updateEndDate"
                                        value="{{ old('updateEndDate', $updateEndDate) }}" autocomplete="off" required />
                                <span class="input-group-append">
                                    <span class="input-group-text bg-light d-block">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" id="reTest" class="btn btn-primary">作成</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-6">
            <div class="d-flex align-items-center">
                <a href="{{ url('admin/exam') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
            </div>
        </div>
        <div class="col-6">
            <div class="d-flex align-items-center justify-content-end">
            @if($retestFlg)
                <button type="button" data-toggle="modal" data-target="#modalDialog"  id="mbtn" class="btn btn-sm btn-success mr-2"><i class="bi bi-arrow-repeat"></i> 再テスト作成</button>
            @endif
                <a data-toggle="modal" href="#myModal1" class="btn btn-sm btn-primary"><i class="bi bi-save"></i> 更新</a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- First Column -->
        <div class="col-12">
            <form action="{{ url('/admin/exam/update') }}" method="POST" id="form">
                {{ csrf_field() }}
                <input type="hidden" name="exam_id" id="exam_id" value="{{ $exam_id }}" />
                <input type="hidden" id="status" name="status" value="{{ $status }}" />
                <input type="hidden" name="question_length" id="question_length" value="{{ $question_length }}">
                <input type="hidden" name="account_length" id="account_length" value="{{ $account_length }}">
                @if ($status == 'not-edit')
                <input type="hidden" name="win_rate" id="win_rate" value="{{ $win_rate }}" />
                <input type="hidden" name="start_dt" value="{{ $start_dt }}" />
                <input type="hidden" name="end_dt" value="{{ $end_dt }}" />
                <input type="hidden" name="duration" id="duration"  value="{{ $duration }}" />
                @endif
                <!-- Custom Text Color Utilities -->
                <div class="card shadow mb-4">
                    <div class="card-header padding-x">
                        <h6 class="m-0 font-weight-bold text-primary">試験基本情報</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6 col-md-12 col-sm-12 mb-4">
                                <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                                    <label for="username1" class="form-label">試験名</label>
                                    <input type="text" id="exam_nm" name="exam_nm"
                                        value="{{ old('exam_nm', $exam_nm) }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 col-md-12 col-sm-12 mb-4">
                                <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                                    <label for="role" class="form-label">合格率</label>
                                    <select name="win_rate" class="form-control" id="role"
                                        {{ $status == 'not-edit' ? 'disabled' : '' }}>
                                        <option value="50" {{ $win_rate == '50' ? 'selected' : '' }}>50%</option>
                                        <option value="60" {{ $win_rate == '60' ? 'selected' : '' }}>60%</option>
                                        <option value="70" {{ $win_rate == '70' ? 'selected' : '' }}>70%</option>
                                        <option value="80" {{ $win_rate == '80' ? 'selected' : '' }}>80%</option>
                                        <option value="90" {{ $win_rate == '90' ? 'selected' : '' }}>90%</option>
                                        <option value="100" {{ $win_rate == '100' ? 'selected' : '' }}>100%
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 col-md-12 col-sm-12 mb-4">
                                <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                                    <label for="exampleInputEmail1" class="form-label">有効期限開始</label>
                                    <div class="input-group date" id="date-picker1">
                                        <input type="text" name="start_dt" class="form-control" id="startDate"
                                            value="{{ old('start_dt', $start_dt) }}" autocomplete="off"
                                            {{ $status == 'not-edit' ? 'disabled' : '' }} required />
                                        <span class="input-group-append">
                                            <span class="input-group-text bg-light d-block">
                                                <i class="bi bi-calendar3"></i>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 col-md-12 col-sm-12 mb-4">
                                <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                                    <label for="exampleInputEmail1" class="form-label">有効期限終了</label>
                                    <div class="input-group date" id="date-picker2">
                                        <input type="text" name="end_dt" class="form-control" id="endDate"
                                            value="{{ old('end_dt', $end_dt) }}" autocomplete="off"
                                            {{ $status == 'not-edit' ? 'disabled' : '' }} required />
                                        <span class="input-group-append">
                                            <span class="input-group-text bg-light d-block">
                                                <i class="bi bi-calendar3"></i>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                                    <label for="role" class="form-label">期間</label>
                                    <select name="duration" class="form-control"
                                        {{ $status == 'not-edit' ? 'disabled' : '' }}>
                                        <option value="300" {{ $duration == '300' ? 'selected' : '' }}>5分</option>
                                        <option value="600" {{ $duration == '600' ? 'selected' : '' }}>10分
                                        </option>
                                        <option value="900" {{ $duration == '900' ? 'selected' : '' }}>15分
                                        </option>
                                        <option value="1200" {{ $duration == '1200' ? 'selected' : '' }}>20分
                                        </option>
                                        <option value="1500" {{ $duration == '1500' ? 'selected' : '' }}>25分
                                        </option>
                                        <option value="1800" {{ $duration == '1800' ? 'selected' : '' }}>30分
                                        </option>
                                        <option value="2100" {{ $duration == '2100' ? 'selected' : '' }}>35分
                                        </option>
                                        <option value="2400" {{ $duration == '2400' ? 'selected' : '' }}>40分
                                        </option>
                                        <option value="2700" {{ $duration == '2700' ? 'selected' : '' }}>45分
                                        </option>
                                        <option value="3000" {{ $duration == '3000' ? 'selected' : '' }}>50分
                                        </option>
                                        <option value="3300" {{ $duration == '3300' ? 'selected' : '' }}>55分
                                        </option>
                                        <option value="3600" {{ $duration == '3600' ? 'selected' : '' }}>60分
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                                    <label for="role" class="form-label">種類</label>
                                    <select class="form-select form-control" name="exam_type" id="exam_type">
                                        {{ $status == 'not-edit' ? 'disabled' : '' }}>
                                        @foreach ($categorylist as $c)
                                        <option value="{{$c->category_id}}" {{ ($c->category_id== $exam_type) ? 'selected' : '' }}>{{$c->category_nm}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header padding-x">
                        <h6 class="m-0 font-weight-bold text-primary">問題追加</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            @if ($status == 'edit')
                            <a href="#ques_modal" id="show_questionList" type="button"
                                class="btn btn-success btn-sm mb-2" data-toggle="modal"><i
                                    class="bi bi-plus-circle"></i>&nbsp;追加</a>
                            <button type="button" name="action" value="removeQuesBtn"
                                class="btn btn-danger btn-sm mb-2" id="question-remove"><i
                                    class="bi bi-dash-circle"></i>&nbsp;取消</button>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-bordered bg-white" width="100%" cellspacing="0"
                                    id="question_tbl">
                                    <tr class="text-brown-color text-size-14">
                                        <th style="width: 5%;">
                                            @if ($status == 'edit')
                                            <input id="ckbCheckAllQuestion" name="select_all"
                                                value="1" type="checkbox">
                                            @endif
                                        </th>
                                        <th style="width: 15%;">No.</th>
                                        <th style="width: 15%;">問題種類</th>
                                        <th style="width: 15%;">問題タイトル</th>
                                        <th style="width: 60%;">問題内容</th>
                                    </tr>
                                    </thead>
                                    <tbody id="question-checkbox">
                                        @foreach ($questionList as $q)
                                        <tr class="text-brown-color text-size-14" id="ques_{{ $q->question_id }}">
                                            <td>
                                                @if ($status == 'edit')
                                                <input type="checkbox" class="form-checkbox"
                                                    name="q_id_chk[]" value="{{ $q->question_id }}">
                                                @else
                                                -
                                                @endif
                                                <input type="hidden" name="q_id_org[]"
                                                    value="{{ $q->question_id }}">
                                            </td>
                                            <td class="checkbox">{{ $loop->index + 1 }}</td>
                                            <td>{{ $q->category_nm }}</td>
                                            <td>{{ $q->title }}</td>
                                            <td>{{ $q->body }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header padding-x">
                        <h6 class="m-0 font-weight-bold text-primary">対象者追加</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <a href="#account_modal" id="show_accountList" type="button"
                                class="btn btn-success btn-sm mb-2" data-toggle="modal"><i
                                    class="bi bi-plus-circle"></i>&nbsp;追加</a>
                            <button type="button" name="action" value="removeAccBtn"
                                class="btn btn-danger btn-sm mb-2" id="account-remove"><i
                                    class="bi bi-dash-circle"></i>&nbsp;取消</button>
                            @if ($status == 'not-edit')
                            <span class="text-danger">&nbsp;&nbsp;<b>※注意</b>：受験のステータスの確認の上、お取消ください。</span>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-bordered bg-white" width="100%" cellspacing="0"
                                    id="account_tbl">
                                    <tr class="text-brown-color text-size-14">
                                        <th style="width: 5%;"><input id="ckbCheckAll" name="select_all"
                                                value="1" type="checkbox"></th>
                                        <th scope="col">No.</th>
                                        <th scope="col">氏名</th>
                                        <th scope="col">グループ</th>
                                        <th scope="col">メール</th>
                                        @if ($status == 'not-edit')
                                        <th scope="col">受験状況</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody id="account-checkbox">
                                        @foreach ($accountList as $a)
                                        <tr class="text-brown-color text-size-14" id="acc_{{ $a->id }}">
                                            <td>
                                                <input type="checkbox" class="form-checkbox" name="acc_id_chk[]"
                                                    value="{{ $a->id }}">
                                                <input type="hidden" name="acc_id_org[]"
                                                    value="{{ $a->id }}">
                                            </td>
                                            <td class="order">{{ $loop->index + 1 }}</td>
                                            <td>{{ $a->name }}</td>
                                            <td>{{ $a->group_name }}</td>
                                            <td>{{ $a->email }}</td>
                                            @if ($status == 'not-edit')
                                            <td>
                                                @if ($a->exam_status == '1')
                                                <span class="text-success">済</span>
                                                @else
                                                <span class="text-danger">未</span>
                                                @endif
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 問題追加Modal -->
                <div class="modal fade" id="ques_modal" aria-hidden="true" aria-labelledby="ques_modal"
                    tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">問題リスト</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                    id="closeQuesModalBtn">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table id="example" class="display select" cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="text-brown-color text-size-14">
                                                <th><input name="select_all" value="1" type="checkbox"></th>
                                                <th>問題種類</th>
                                                <th>問題タイトル</th>
                                                <th style="width: 55%;">問題内容</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($questions as $q)
                                            <tr class="text-brown-color text-size-14">
                                                <td>{{ $q->question_id }}</td>
                                                <td>{{ $q->question_type_nm }}</td>
                                                <td>{{ $q->title }}</td>
                                                <td style="width: 55%;">{{ $q->body }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-start">
                                <button type="button" name="action" class="btn btn-success" value="addQuesBtn"
                                    id="question-modal-add">追加</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- アカウント追加Modal -->
                <div class="modal fade" id="account_modal" aria-hidden="true" aria-labelledby="account_modal"
                    tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">対象者リスト</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                    id="closeAccModalBtn">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table id="dataTable2" class="display select" cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="text-brown-color text-size-14">
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="width: 20%;">氏名</th>
                                                <th>グループ</th>
                                                <th>メール</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accounts as $a)
                                            <tr class="text-brown-color text-size-14">
                                                <td>{{ $a->id }}</td>
                                                <td style="width: 20%;">{{ $a->name }}</td>
                                                <td>{{ $a->group_name }}</td>
                                                <td>{{ $a->email }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-start">
                                <button type="button" name="action" class="btn btn-success" value="addAccBtn"
                                    id="account-modal-add">追加</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal" id="myModal1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="border: none;">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                    id="closeBtn">×</button>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body" style="text-align:center;">
                                <p>
                                    更新します。よろしいでしょうか？
                                </p>
                            </div>
                            <div class="modal-footer" style="border: none;">
                                <button type="submit" id="formSubmit" class="btn btn-primary">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $('#date-picker1').datepicker({
        format: 'yyyy/mm/dd',
        autoclose: true,
        todayHighlight: true,
    }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#date-picker2').datepicker('setStartDate', minDate);
    });
    $('#date-picker2').datepicker({
        format: 'yyyy/mm/dd',
        autoclose: true,
        todayHighlight: true,
    }).on('changeDate', function(selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#date-picker1').datepicker('setEndDate', maxDate);
    });

    $(document).ready(function() {
        var status = $('#status').val();
        var acc_id_arr = $('#acc_id_arr').val();
        if (status == 'not-edit') {
            if (acc_id_arr != '') {
                var accIdList = acc_id_arr.split(',');
                for (let i = 0; i < accIdList.length; i++) {
                    $("#acc_td_" + accIdList[i]).text('-')
                }
            }
        }
    });
</script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables-demo.js') }}"></script>
<script>
    function updateDataTableSelectAllCtrl(table) {
        var $table = table.table().node();
        var $chkbox_all = $('tbody input[type="checkbox"]', $table);
        var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
        var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

        // If none of the checkboxes are checked
        if ($chkbox_checked.length === 0) {
            chkbox_select_all.checked = false;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length) {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If some of the checkboxes are checked
        } else {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = true;
            }
        }
    }

    $(document).ready(function() {

        var modal = $('#modalDialog');
        var span = $(".close");
        $('#date-picker3').datepicker({
            format: 'yyyy/mm/dd',
            autoclose: true,
            todayHighlight: true,
            startDate: 'today'
        }).on('changeDate', function(selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#updateEndDate').datepicker('setStartDate', minDate);
        });

        $('#date-picker4').datepicker({
            format: 'yyyy/mm/dd',
            autoclose: true,
            todayHighlight: true,
            startDate: 'today'
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // When the user clicks on <span> (x), close the modal
        span.on('click', function() {
            modal.fadeOut();
        });

        // When the user clicks anywhere outside of the modal, close it
        $('body').bind('click', function(e) {
            if ($(e.target).hasClass("modal")) {
                modal.fadeOut();
            }
        });

        $("#show_accountList").click(function() {
            $('#account-modal-add').removeAttr("disabled");
            $('#question-modal-add').removeAttr("disabled");
        });


        $("#show_questionList").click(function() {
            $('#account-modal-add').removeAttr("disabled");
            $('#question-modal-add').removeAttr("disabled");
        });

        $('input[name="select_all"]').click(function() {
            $('#account-modal-add').removeAttr("disabled");
            $('#question-modal-add').removeAttr("disabled");
        });
        // Array holding selected row IDs
        var rows_selected = [];
        var table = $('#example').DataTable({
            'ordering': false,
            //'ajax': 'https://gyrocode.github.io/files/jquery-datatables/arrays_id.json',
            'columnDefs': [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'width': '1%',
                'className': 'dt-body-center',
                'render': function(data, type, full, meta) {
                    return '<input type="checkbox">';
                }
            }],
            // 'order': [1, 'asc'],
            'rowCallback': function(row, data, dataIndex) {
                // Get row ID
                var rowId = data[0];

                // If row ID is in the list of selected row IDs
                if ($.inArray(rowId, rows_selected) !== -1) {
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            }
        });

        //check all checkboxes for question for thead
        $("#ckbCheckAllQuestion").click(function() {
            if (this.checked) {
                $('#question_tbl tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#question_tbl tbody input[type="checkbox"]:checked').trigger('click');
            }
        });

        // Handle click on checkbox
        $('#example tbody').on('click', 'input[type="checkbox"]', function(e) {
            var $row = $(this).closest('tr');

            // Get row data
            var data = table.row($row).data();

            // Get row ID
            var rowId = data[0];

            // Determine whether row ID is in the list of selected row IDs 
            var index = $.inArray(rowId, rows_selected);

            // If checkbox is checked and row ID is not in list of selected row IDs
            if (this.checked && index === -1) {
                rows_selected.push(rowId);

                // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1) {
                rows_selected.splice(index, 1);
            }

            if (this.checked) {
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }

            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle click on table cells with checkboxes
        $('#example').on('click', 'tbody td, thead th:first-child', function(e) {
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', table.table().container()).on('click', function(e) {
            if (this.checked) {
                $('#example tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#example tbody input[type="checkbox"]:checked').trigger('click');
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        table.on('draw', function() {
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
        });

        //remove chosen question list
        $("#question-remove").click(function() {

            $("#question-checkbox tr").each(function() {

                if ($(this).find("input:checkbox:checked").length > 0) {
                    var $this = $(this);
                    var $tr = $this.closest('tr');
                    var currentIndex = $tr.index();
                    // alert(currentIndex);
                    // First of all re-index next items
                    // You won t see any difference if you have, let say, less than 2-3k  rows
                    $tr.nextAll().find('.checkbox').each(function(i) {
                        $(this).text(i + currentIndex + 1);
                    })
                    $(this).remove();

                }

            })

        });

        //remove all selected checkbox
        $(".toggleCheckbox").change(function() {

            $("#tablaNorma tbody tr").find("input:checkbox").prop("checked", this.checked);
        })

        // Handle form submission event 
        $('#question-modal-add').on('click', function(e) {

            $.ajax({
                type: "post",
                url: "{{ route('admin.questionadd') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    rows_selected: rows_selected
                },
                success: function(data) {
                    $('#ques_modal').modal('hide');
                    $.each(data, function(j, itemm) {
                        var rowCount = $('#question-checkbox').children().length +
                            1;
                        var id = itemm.question_id;
                        var alreadyExits = false;
                        $('#question_tbl tr').each(function(j, row) {
                            var $row = $(row); //console.log("SDF"+item);
                            $checkedBoxes = $row.find(
                                'input[name*="q_id_chk"]');
                            $checkedBoxes.each(function(i, checkbox) {

                                if ($(checkbox).val() == id) {
                                    alreadyExits = true;

                                }
                            })
                        })
                        if (!alreadyExits) {
                            $("#question_tbl #question-checkbox ").append(
                                "<tr><td><input type='checkbox' class='form-checkbox' name='q_id_chk[]' value='" +
                                itemm.question_id + "'>" +
                                "<input type='hidden' class='form-checkbox' name='q_id_org[]' value='" +
                                itemm.question_id + "'>" +
                                "</td><td>" + rowCount + "</td><td>" +
                                itemm.category_nm + "</td><td>" + itemm
                                .title +
                                "</td><td>" + itemm.body + "</td></tr>");
                            rowCount += 1;
                        }

                    })

                    $("#question-remove").removeAttr("disabled");

                },
                error: function(err) {
                    console.log(err);
                }

            });


        });

        //account 
        var rows_selected_acc = [];
        var table2 = $('#dataTable2').DataTable({
            'ordering': false,
            'lengthChange': false,
            //'ajax': 'https://gyrocode.github.io/files/jquery-datatables/arrays_id.json',
            'columnDefs': [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'width': '1%',
                'className': 'dt-body-center',
                'render': function(data, type, full, meta) {
                    return '<input type="checkbox">';
                }
            }],
            // 'order': [1, 'asc'],
            'rowCallback': function(row, data, dataIndex) {
                // Get row ID
                var rowId = data[0];

                // If row ID is in the list of selected row IDs
                if ($.inArray(rowId, rows_selected_acc) !== -1) {
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            }
        });

        // Handle click on checkbox
        $('#dataTable2 tbody').on('click', 'input[type="checkbox"]', function(e) {
            var $row = $(this).closest('tr');

            // Get row data
            var data = table2.row($row).data();

            // Get row ID
            var rowId = data[0];

            // Determine whether row ID is in the list of selected row IDs 
            var index = $.inArray(rowId, rows_selected_acc);

            // If checkbox is checked and row ID is not in list of selected row IDs
            if (this.checked && index === -1) {
                rows_selected_acc.push(rowId);

                // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1) {
                rows_selected_acc.splice(index, 1);
            }

            if (this.checked) {
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }

            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table2);

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle click on table cells with checkboxes
        $('#dataTable2').on('click', 'tbody td, thead th:first-child', function(e) {
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', table2.table().container()).on('click', function(e) {
            if (this.checked) {
                $('#dataTable2 tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#dataTable2 tbody input[type="checkbox"]:checked').trigger('click');
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });


        // Handle table draw event
        table2.on('draw', function() {
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table2);
        });

        //check all checkboxes for account for thead
        $("#ckbCheckAll").click(function() {
            if (this.checked) {
                $('#account_tbl tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#account_tbl tbody input[type="checkbox"]:checked').trigger('click');
            }
        });
        $('#account-modal-add').on('click', function(e) {
            $.ajax({
                type: "post",
                url: "{{ route('admin.accountadd') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    rows_selected_acc: rows_selected_acc
                },
                success: function(data) {
                    //console.log(data);
                    $('#account_modal').modal('hide');
                    $.each(data, function(j, itemm) {
                        var rowCount = $('#account-checkbox').children().length + 1;

                        var id = itemm.id;
                        var alreadyExits = false;
                        $('#account_tbl tr').each(function(j, row) {
                            var $row = $(row);
                            $checkedBoxes = $row.find(
                                'input[name*="acc_id_chk"]');
                            $checkedBoxes.each(function(i, checkbox) {

                                if ($(checkbox).val() == id) {
                                    alreadyExits = true;

                                }
                            })
                        })
                        if (!alreadyExits) {
                            $("#account_tbl #account-checkbox").append(
                                "<tr class='text-brown-color text-size-14'><td><input type='checkbox' class='form-checkbox' name='acc_id_chk[]' value='" +
                                itemm.id + "'>" +
                                "<input type='hidden' class='form-checkbox' name='acc_id_org[]' value='" +
                                itemm.id + "'>" +
                                "</td><td class='order'>" + rowCount +
                                "</td><td>" +
                                itemm.name +
                                "</td><td>" +
                                itemm.group_name +
                                "</td><td>" + 
                                itemm.email +
                                "</td><td><span class='text-danger'>未</span></td></tr>");
                            rowCount += 1;
                        }

                    })

                    $("#account-remove").removeAttr("disabled");
                },
                error: function(err) {
                    console.log(err);
                }

            });

        });

        //remove chosen question list
        $("#account-remove").click(function() {
            $("#account_tbl #account-checkbox tr").each(function() {
                if ($(this).find("input:checkbox:checked").length > 0) {

                    var $this = $(this);
                    var $tr = $this.closest('tr');
                    var currentIndex = $tr.index();
                    $tr.nextAll().find('.order').each(function(i) {
                        $(this).text(i + currentIndex + 1);
                    })
                    $(this).remove();
                }
            });
        });

        //remove all selected checkbox
        $(".toggleCheckbox").change(function() {

            $("#tablaNorma tbody tr").find("input:checkbox").prop("checked", this.checked);
        })


    });

    $("#formSubmit").click(function() {
        var exam_nm = $("#exam_nm").val();
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();

        if (exam_nm == '' || startDate == '' || endDate == '') {
            $("#closeBtn").click();
            alert("試験情報を入力してください。")
            return false;
        }
        var question_length = $("#question-checkbox  tr").length;
        var account_length = $("#account-checkbox  tr").length;
        if (question_length <= 0) {
            $("#closeBtn").click();
            alert("問題を最低1問追加してください。")
            return false;
        }
        if (account_length <= 0) {
            $("#closeBtn").click();
            alert("アカウントを最低1人追加してください。")
            return false;
        }
        $("#form").submit();
    });
</script>
{{-- <script>
        // tell the embed parent frame the height of the content
        if (window.parent && window.parent.parent) {
            window.parent.parent.postMessage(["resultsFrame", {
                height: document.body.getBoundingClientRect().height,
                slug: "abhbs4x8"
            }], "*")
        }

        // always overwrite window.name, in case users try to set it manually
        window.name = "result"
    </script> --}}
@endsection