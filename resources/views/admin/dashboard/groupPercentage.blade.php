@php
    $user = \Illuminate\Support\Facades\Auth::user();
@endphp

@if ($user->is_admin == 1)
<div style="margin-bottom: 25px;">
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">グループ</h6>
        </div>

        <div class="card-body">
            <canvas id="myBarChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>
@endif

<script>
    const barCtx = document.getElementById('myBarChart').getContext('2d');
    fetch('/admin/groupPercent')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.group_name);
            const voteCounts = data.map(item => item.count);
            const myBarChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'グループ人数',
                        data: voteCounts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1,
                        borderRadius: 2 // Rounded edges for a softer look
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                display: true,
                                stepSize: 5,
                                callback: function(value) {
                                    return value + "人"; 
                                },
                            },
                            grid: {
                                display: true 
                            }
                        },
                        x: {
                            ticks: {
                                display: true // Show x-axis labels
                            },
                            grid: {
                                display: false // Hide x-axis gridlines
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw + '人';
                                }
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        })
        .catch(error => console.error('Error fetching data:', error));
</script>