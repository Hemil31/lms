fetch('api/bookchart')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const chartData = {
                labels: ['Deleted', 'Active', 'Inactive'],
                datasets: [{
                    data: [
                        data.data.deleted,
                        data.data.active,
                        data.data.inactive
                    ],
                    backgroundColor: ['#ff6347', '#32cd32', '#ffd700'],
                    borderColor: ['#ff0000', '#228b22', '#ff8c00'],
                    borderWidth: 1
                }]
            };
            const options = {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'center',
                    },
                },

            };
            const ctx = document.getElementById('bookPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: chartData,
                options: options
            });
            const totalBooks = data.data.total;
            document.getElementById('totalBook').innerHTML = `Total Books: ${totalBooks}`;
        } else {
            console.error('API Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });
