<div style="margin-bottom: 25px;">
    <div class="card shadow">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 d-flex align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">試験状況</h6>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Line Chart -->
            <canvas id="myLineChart" height="70"></canvas>

            <!-- すべて -->
            <div class="d-flex justify-content-end mt-4 mb-2">
                <a href="{{ url('/admin/dashboard/exam-list') }}" class="btn btn-sm btn-primary float-right">すべて</a>
            </div>
            <div class="table-responsive dataTables_Tablet">
                <!-- Table -->
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
                        @if(!empty($examList))
                        @foreach($examList as $data)
                        <tr class="text-brown-color text-size-14">
                            <td>{{ $loop->index +1 }}</td>
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
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Line Chart: Exam Pass vs Fail
    const lineCtx = document.getElementById('myLineChart').getContext('2d');

    fetch('/admin/examGroup')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.exam_nm.substring(0, 7) + '...');
            const fullLabels = data.map(item => item.exam_nm);
            const passCounts = data.map(item => item.passed_count);
            const failCounts = data.map(item => item.failed_count);

            const lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '合格',
                            data: passCounts,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.3
                        },
                        {
                            label: '不合格',
                            data: failCounts,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return fullLabels[context.dataIndex] + ': ' + context.raw + '人';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => value + '人'
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
</script>