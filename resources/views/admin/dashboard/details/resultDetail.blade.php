<div class="mt-5"></div>
<h6 class="m-0 font-weight-bold text-primary">試験結果</h6>
<div class="mb-2"></div>
<div class="table-responsive dataTables_Tablet text-size-14">
    <table class="table table-bordered bg-white data-table" width="100%" cellspacing="0">
        <thead>
            <tr class="text-brown-color">
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
        <tbody class="text-brown-color">
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
                        class="btn btn-sm btn-primary shadow"><i class="fa fa-eye"></i></a>
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>