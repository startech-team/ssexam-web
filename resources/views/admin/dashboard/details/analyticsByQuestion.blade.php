<div class="card mb-5 shadow">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">試験の問題</h6>
    </div>
    @foreach ($examListByID as $exam)
    <input type="hidden" id="examID" value="{{ $exam->exam_id }}" />
    @endforeach
    <div class="card-body">
        <canvas id="myBarChart" height="70"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('myBarChart').getContext('2d');
    const examID = document.getElementById('examID').value;

    fetch('/admin/anatyticsByQuestion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                examID: examID
            })
        })
        .then(response => response.json())
        .then(data => {
            const labels = data.map((item,index) => "問 " + (index + 1));
            const incorrectCounts = data.map(item => item.incorrect_count);
            const correctCounts = data.map(item => item.correct_count); // Assuming you have this in your data

            // Define the chart as myBarChart
            const myBarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: '不正数', // Incorrect counts
                            data: incorrectCounts,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: '正解数', // Correct counts
                            data: correctCounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5,
                                display: true,
                                callback: function(value) {
                                    return value + "人"; // Append "人" to each tick label
                                },
                            },
                            grid: {
                                display: false // Hide y-axis gridlines
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
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': ' + tooltipItem.raw + '人'; // Customize tooltip
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