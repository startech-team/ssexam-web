@extends('layouts.default')

@section('content')
    <div class="container-fluid mb-5">
    @if(auth()->user()->is_admin == 1)
        <!-- Content Row -->
        <div class="row">

            <!-- 社員数-->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    利用者数</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userList }} 人</div>
                            </div>
                            <div class="col-auto">
                                <i class='fas fa-user-friends fa-2x text-gray-400'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- グループ数 -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    グループ</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $groupList }} グループ</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-object-ungroup fa-2x text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 実施中の試験 -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    実施中の試験</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $examList }} 試験</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book-open fa-2x text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 問題数 -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    問題数</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $questionList }} 問</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-question-circle fa-2x text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- report -->
        <form action="{{ url('/admin') }}" method="get" id="searchform" name="searchform">
            
            <div class="card" style="margin-bottom:30px">

                <div class="card-body">
                    <div class="form-group mx-sm-3 mb-2">
                        <div class="row">
                            <div class="col">
                                <input type="text" style="width:60%;" name="username" class="form-control"
                                    placeholder="User name">
                            </div>
                            <div class="col">
                                <input type="text" style="width:60%;" name="group_id" class="form-control"
                                    placeholder="Group Id">
                            </div>
                            <div class="col">
                                <input type="text" style="width:60%;" name="exam_id" class="form-control"
                                    placeholder="Exam Id">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive dataTables_Tablet">
            <table class="table table-bordered bg-white data-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>氏名</th>
                        <th>グループ</th>
                        <th>試験名</th>
                        <th>試験期間</th>
                        <th>試験結果</th>
                        <th>点数</th>
                        <th>受験日</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($userexams) == 0)
                    <tr>
                    <td colspan="8" style="text-align: center;"><span style="color: crimson;">Empty Data</span></td>
                    @else
                        @foreach ($userexams as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->group_name }}</td>
                                <td>{{ $u->exam_nm }}</td>
                                <td>{{ ($u->duration)/60 }} 分</td>
                                <td>
                                    @if ($u->result == '合格')
                                        <span class="text-success "><strong>{{ $u->result }}</strong></span>
                                    @elseif($u->result == '不合格')
                                        <span class="text-danger "><strong>{{ $u->result }}</strong></span>
                                    @elseif($u->result == '試験中')
                                        <span class="text-primary"><strong>{{ $u->result }}</strong></span>
                                    @else
                                        <span>{{ $u->result }}</span>
                                    @endif
                                </td>
                                <td>{{ $u->mark }}</td>
                                <td>{{ $u->take_exam_dt }}</td>
                                <td>
                                    @if ($u->result == '-')
                                        <span>-</span>
                                    @elseif($u->result == '試験中')
                                        <span>-</span>
                                    @else
                                        <a href="{{ url('/admin/dashboard/detail/' . $u->acc_id . '/' . $u->exam_id) }}"
                                            class="btn btn-success btn-sm">詳細</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        {{ $userexams->links() }}
    </div>


@endsection
