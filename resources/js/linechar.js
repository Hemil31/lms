let userActivityChartInstance = null;
function fetchUserActivityData(interval = 'yearly') {
    const url = `api/userchart?interval=${interval}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const labels = data.data.map(item => `User ID: ${item.merged_user_ids}`);
            const values = data.data.map(item => item.borrows_count);
            const topUser = data.data.reduce((max, item) =>
                item.borrows_count > max.borrows_count ? item : max,
                data.data[0]
            );
            document.getElementById('topUserDetails').innerHTML = `<strong>Top User:</strong> ${topUser.merged_user_ids}`;
            const chartData = {
                labels: labels,
                datasets: [{
                    label: 'Borrows Count',
                    data: values,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    hoverBackgroundColor: 'rgba(75, 192, 192, 0.8)',
                    pointRadius: 6,
                }]
            };
            const chartOptions = {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { size: 14 }
                        }
                    },
                },
                scales: {
                    x: {
                        grid: { display: true },
                        ticks: { display: true }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { display: true, borderColor: '#ddd', lineWidth: 1 },
                        ticks: { font: { size: 14 } }
                    }
                }
            };
            if (!userActivityChartInstance) {
                const ctx = document.getElementById('userActivityChart').getContext('2d');
                userActivityChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: chartOptions
                });
            } else {
                userActivityChartInstance.data = chartData;
                userActivityChartInstance.options = chartOptions;
                userActivityChartInstance.update();
            }
        })
        .catch(error => {
            console.error('Error fetching user activity data:', error);
        });
}

fetchUserActivityData('yearly');
document.getElementById('user-activity-interval').addEventListener('change', function () {
    const interval = this.value;
    fetchUserActivityData(interval);
});
