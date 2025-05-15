import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    fetch('/admin/stats/data')
        .then(res => res.json())
        .then(data => {
            const ctx1 = document.getElementById('chartTrajets');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: data.trajets.labels,
                        datasets: [{
                            label: 'Covoiturages réalisés',
                            data: data.trajets.values,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            const ctx2 = document.getElementById('chartCredits');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: data.credits.labels,
                        datasets: [{
                            label: 'Crédits gagnés',
                            data: data.credits.values,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        });
});
