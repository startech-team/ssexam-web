
<div class="row mb-4">
    <div class="col-md-6 d-flex">
        <div class="card h-100 w-100 shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">合格/不合格</h6>
            </div>
            <div class="card-body" style="width: 55%; height: 55%;">
                <canvas id="myPieChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 d-flex">
        @foreach ($examListByID as $data )
            <div class="card h-100 w-100 shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">試験詳細</h6>
                </div>
                <div class="card-body text-size-14 text-brown-color">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-4">
                            <span>試験名</span>
                        </div>
                        <div class="col-md-8 text-right">
                            <span>{{$data->exam_nm}}</span>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-4">
                            <span>有効期間</span>
                        </div>
                        <div class="col-md-8 text-right">
                            <span>{{$data->start_dt}}~{{$data->end_dt}}</span>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-4">
                            <span>期間</span>
                        </div>
                        <div class="col-md-8 text-right">
                            <span>{{$data->duration}}日</span>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-4">
                            <span>問題数</span>
                        </div>
                        <div class="col-md-8 text-right">
                            <span>{{$data->ques_count}}問</span>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-4">
                            <span>合格率</span>
                        </div>
                        <div class="col-md-8 text-right">
                            <span>{{$data->win_rate}}%</span>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-4">
                            <span>対象者</span>
                        </div>
                        <div class="col-md-8 text-right">
                            <span>{{$data->acc_count}}人</span>
                        </div>
                    </div>
                </div>
        </div>
        @endforeach
    </div>
    <input type="hidden" id="examID" value="{{ $examID }}">
</div>

<script>
    const ctxx = document.getElementById('myPieChart').getContext('2d');
    fetch('/admin/examPieChart/' + document.getElementById('examID').value)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            const failedCount = data.failed_count;
            const passedCount = data.passed_count;
            const total = failedCount + passedCount;
            const myPieChart = new Chart(ctxx, {
                type: 'pie',
                data: {
                    labels: ['不合格', '合格'],
                    datasets: [{
                        data: [failedCount, passedCount],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins:{
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                display: true,
                                stepSize: 5,
                                callback: function(value) {
                                    return value + "%"; // Append "%" to each tick label
                                },
                            },
                            display: false
                        },
                        x: {
                            ticks: {
                                display: false // Show x-axis labels
                            },
                            grid: {
                                display: false // Hide x-axis gridlines
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        })
        .catch(error => console.error('Error fetching data:', error));
</script>