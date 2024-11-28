let chartInstance = null;
function randomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
function fetchData(interval = 'yearly') {
    const url = `api/borrowedchart?interval=${interval}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const labels = data.data.map((item, index) => `Book ${item.merged_book_ids}`);
            const values = data.data.map(item => item.borrows_count);
            const highestBorrowed = data.data.reduce((max, item) => item.borrows_count > max.borrows_count ?
                item : max, data.data[0]);
            document.getElementById('highestBorrowedBook').innerHTML = `
                <strong>Highest Borrowed Book:</strong> ${highestBorrowed.merged_book_ids}`;
            const backgroundColors = data.data.map(() => randomColor());
            const ctx = document.getElementById('borrowedChart').getContext('2d');
            if (chartInstance) {
                chartInstance.destroy();
            }
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Borrowed Count per Book',
                        data: values,
                        backgroundColor: backgroundColors,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        hoverBackgroundColor: 'rgba(54, 162, 235, 0.8)',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: true,
                                borderColor: '#ddd',
                                lineWidth: 1
                            },
                            ticks: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                borderColor: '#ddd',
                                lineWidth: 1
                            },
                            ticks: {
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}
fetchData('yearly');
document.getElementById('interval').addEventListener('change', function () {
    const interval = this.value;
    fetchData(interval);
});
