@extends('layouts.default')

@section('content')
    <div class="container-fluid">

        <div class="row mb-2">
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <a href="{{ url('admin/exam') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left"></i>戻る</a>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex align-items-center justify-content-end">
                    <a data-toggle="modal" href="#myModal1" class="btn btn-sm btn-primary"><i class="bi bi-save"></i> 登録</a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- First Column -->
            <div class="col-12">
                <form id="frm-example" action="{{ url('/admin/exam/store') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="question_length" id="question_length" value="{{ $question_length }}">
                    <input type="hidden" name="account_length" id="account_length" value="{{ $account_length }}">
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
                                        <input type="text" name="exam_nm" value="{{ old('exam_nm', $exam_nm) }}"
                                            class="form-control" id="exam_nm" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-md-12 col-sm-12 mb-4">
                                    <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                                        <label for="role" class="form-label">合格率</label>
                                        <select name="win_rate" class="form-control" id="role">
                                            <option value="50"
                                                {{ old('win_rate', $win_rate) == '50' ? 'selected=' . '"' . 'selected' . '"' : '' }}>
                                                50%</option>
                                            <option value="60"
                                                {{ old('win_rate', $win_rate) == '60' ? 'selected=' . '"' . 'selected' . '"' : '' }}>
                                                60%</option>
                                            <option value="70"
                                                {{ old('win_rate', $win_rate) == '70' ? 'selected=' . '"' . 'selected' . '"' : '' }}>
                                                70%</option>
                                            <option value="80"
                                                {{ old('win_rate', $win_rate) == '80' ? 'selected=' . '"' . 'selected' . '"' : '' }}>
                                                80%</option>
                                            <option value="90"
                                                {{ old('win_rate', $win_rate) == '90' ? 'selected=' . '"' . 'selected' . '"' : '' }}>
                                                90%</option>
                                            <option value="100"
                                                {{ old('win_rate', $win_rate) == '100' ? 'selected=' . '"' . 'selected' . '"' : '' }}>
                                                100%</option>
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
                                                value="{{ old('start_dt', $start_dt) }}" autocomplete="off" required />
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
                                                value="{{ old('end_dt', $end_dt) }}" autocomplete="off" required />
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
                                        <select name="duration" class="form-control" id="role">
                                            <option value="300"
                                                {{ old('duration', $duration) == '300' ? 'selected' : '' }}>5分</option>
                                            <option value="600"
                                                {{ old('duration', $duration) == '600' ? 'selected' : '' }}>10分</option>
                                            <option value="900"
                                                {{ old('duration', $duration) == '900' ? 'selected' : '' }}>15分</option>
                                            <option value="1200"
                                                {{ old('duration', $duration) == '1200' ? 'selected' : '' }}>20分</option>
                                            <option value="1500"
                                                {{ old('duration', $duration) == '1500' ? 'selected' : '' }}>25分</option>
                                            <option value="1800"
                                                {{ old('duration', $duration) == '1800' ? 'selected' : '' }}>30分</option>
                                            <option value="2100"
                                                {{ old('duration', $duration) == '2100' ? 'selected' : '' }}>35分</option>
                                            <option value="2400"
                                                {{ old('duration', $duration) == '2400' ? 'selected' : '' }}>40分</option>
                                            <option value="2700"
                                                {{ old('duration', $duration) == '2700' ? 'selected' : '' }}>45分</option>
                                            <option value="3000"
                                                {{ old('duration', $duration) == '3000' ? 'selected' : '' }}>50分</option>
                                            <option value="3300"
                                                {{ old('duration', $duration) == '3300' ? 'selected' : '' }}>55分</option>
                                            <option value="3600"
                                                {{ old('duration', $duration) == '3600' ? 'selected' : '' }}>60分</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-md-12 col-sm-12 mb-3">
                                    <div class="col-12 col-lg-8 col-md-12 col-sm-12">
                                        <label for="role" class="form-label">種類</label>
                                        <select class="form-select form-control" name="exam_type" id="exam_type">
                                            @foreach ($categorylist as $c)
                                            <option value="{{$c->category_id}}">{{$c->category_nm}}</option>
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
                                <a href="#ques_modal"  id="show_questionList" type="button" class="btn btn-success btn-sm mb-2"
                                    data-toggle="modal"><i class="bi bi-plus-circle"></i>&nbsp;追加</a>
                                <button type="button" name="action" value="removeQuesBtn"
                                    class="btn btn-danger btn-sm mb-2" id="question-remove"><i
                                        class="bi bi-dash-circle"></i>&nbsp;取消</button>
                                <div class="table-responsive">
                                    <table class="table table-bordered bg-white" id="question_tbl" width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <tr class="text-brown-color text-size-14">
                                                <th style="width: 5%;">#</th>
                                                <th style="width: 15%;">No.</th>
                                                <th style="width: 15%;">問題種類</th>
                                                <th style="width: 15%;">問題タイトル</th>
                                                <th style="width: 50%;">問題内容</th>
                                            </tr>
                                        </thead>
                                        <tbody id="question-checkbox" class="text-brown-color text-size-14">

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
                                <a href="#account_modal" id="show_accountList" type="button" class="btn btn-success btn-sm mb-2"
                                    data-toggle="modal"><i class="bi bi-plus-circle"></i>&nbsp;追加</a>
                                <button type="button" name="action" value="removeAccBtn"
                                    class="btn btn-danger btn-sm mb-2" id="account-remove"><i
                                        class="bi bi-dash-circle"></i>&nbsp;取消</button>
                                <div class="table-responsive">
                                    <table class="table table-bordered bg-white" id="account_tbl" width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <tr class="text-brown-color text-size-14">
                                                <th style="width: 5%;">#</th>
                                                <th scope="col">No.</th>
                                                <th scope="col">氏名</th>
                                                <th scope="col">グループ</th>
                                                <th scope="col">メール</th>
                                            </tr>
                                        </thead>
                                        <tbody id="account-checkbox" class="text-brown-color text-size-14">

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
                                    <h5 class="modal-title" id="ques_modal">問題リスト</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                        id="closeQuesModalBtn">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive dataTables_Tablet">
                                        <table id="example" class="display select" cellspacing="0" width="100%">
                                            <thead>
                                                <tr class="text-brown-color text-size-14">
                                                    <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                    <th style="width: 20%;">問題種類</th>
                                                    <th style="width: 20%;">問題タイトル</th>
                                                    <th style="width: 55%;">問題内容</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($questions as $q)
                                                    <tr class="text-brown-color text-size-14">
                                                        <td>{{ $q->question_id }}</td>
                                                        <td>{{ $q->category_nm }}</td>
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
                                    <h5 class="modal-title" id="account_modal">対象者リスト</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                        id="closeAccModalBtn">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive dataTables_Tablet">

                                        <table id="dataTable2" class="display select" cellspacing="0" width="100%">
                                            <thead>
                                                <tr class="text-brown-color text-size-14">
                                                    <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                    <th style="width: 20%;">氏名</th>
                                                    <th style="width: 20%;">グループ</th>
                                                    <th style="width: 55%;">メール</th>

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
                                        登録します。よろしいでしょうか？
                                    </p>
                                </div>
                                <div class="modal-footer" style="border: none;">
                                    <button type="submit" name="action" id="formSubmit"
                                        class="btn btn-primary">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //<![CDATA[


        //
        // Updates "Select all" control in a data table
        //
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
           var question_length = $('#question_tbl tbody').find('tr').length;
           if(question_length==0){
            $( "#question-remove" ).prop( "disabled", true );
           }
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
                'lengthChange': false,
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
                // Disable sorting
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

                $("#question_tbl tbody tr").each(function() {

                    if ($(this).find("input:checkbox:checked").length > 0) 
                    {
                        
                        var $this = $(this);
                        var $tr = $this.closest('tr');
                        var currentIndex = $tr.index();
                        // First of all re-index next items
                        // You won t see any difference if you have, let say, less than 2-3k  rows
                       $tr.nextAll().find('.order').each(function(i){
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
                if(rows_selected.length==0){
                    alert("質問を選択してください");
                    return false;
                };
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
                        //console.log(data);
                        $("#question-checkbox").empty();

                        $('#ques_modal').modal('hide');

                        //datavar obj = jQuery.parseJSON(data);
                        $.each(data, function(i, item) {

                            var i = i + 1;
                            $("#question_tbl tbody").append(
                                "<tr><td><input type='checkbox' class='form-checkbox' name='q_id_chk[]' value='" +
                                item.question_id + "'>" +
                                "<input type='hidden' class='form-checkbox' name='q_id_chk_hidden[]' value='" +
                                item.question_id + "'>" +
                                "</td><td class='order'>" + i + "</td><td>" +
                                item.category_nm + "</td><td>" + item.title +
                                "</td><td>" + item.body + "</td></tr>");
                        });
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
                // Disable sorting
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

            $('#account-modal-add').on('click', function(e) {
                if(rows_selected_acc.length==0){
                    alert("ユーザーを選択してください。");
                    return false;
                }
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


                        $("#account-checkbox").empty();
                        $('#account_modal').modal('hide');

                        //datavar obj = jQuery.parseJSON(data);
                        $.each(data, function(i, item) {
                            // console.log(item);
                            var i = i + 1;
                            $("#account_tbl tbody").append(
                                "<tr><td><input type='checkbox' class='form-checkbox' name='acc_id_chk[]' value='" +
                                item.id + "'>" +
                                "<input type='hidden' class='form-checkbox' name='acc_id_chk_hidden[]' value='" +
                                item.id + "'>" +
                                "</td><td class='order'>" + i + "</td><td>" +
                                item.name + "</td><td>" + item.group_name +
                                "</td><td>" + item.email + "</td></tr>");
                        });
                        $("#account-remove").removeAttr("disabled");
                    },
                    error: function(err) {
                        console.log(err);
                    }

                });

            });

            //remove chosen question list
            $("#account-remove").click(function() {


                $("#account_tbl tbody tr").each(function() {

                    if ($(this).find("input:checkbox:checked").length > 0) 
                    {
                        var $this = $(this);
                        var $tr = $this.closest('tr');
                        var currentIndex = $tr.index();
                        // First of all re-index next items
                        // You won t see any difference if you have, let say, less than 2-3k  rows
                       $tr.nextAll().find('.order').each(function(i){
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
        });

        //]]>
    </script>
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
    </script>
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables-demo.js') }}"></script>
    <script>
        $("#formSubmit").click(function() {
            var exam_nm = $("#exam_nm").val();
            var startDate = $("#startDate").val();
            var endDate = $("#endDate").val();

            if (exam_nm == '' || startDate == '' || endDate == '') {
                $("#closeBtn").click();
                alert("試験情報を入力してください。")
                return false;
            }

            var question_length = $('#question_tbl tbody').find('tr').length;
            var account_length = $("#account_tbl tbody").find('tr').length;
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
            $("#frm-example").submit();
        });
    </script>
@endsection
