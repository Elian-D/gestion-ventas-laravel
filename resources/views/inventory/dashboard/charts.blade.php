
    <script>
        window.dashboardData = {
            history: JSON.parse('{!! addslashes(json_encode($charts["history"])) !!}'),
            distribution: JSON.parse('{!! addslashes(json_encode($charts["distribution"])) !!}'),
            topProducts: JSON.parse('{!! addslashes(json_encode($charts["top_products"])) !!}')
        };
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const data = window.dashboardData;

            // Configuración Global para simular diseño de la imagen
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b';

            // 1. Flujo de Inventario (Line/Area)
            new Chart(document.getElementById('chart-movements'), {
                type: 'line',
                data: {
                    labels: data.history.labels,
                    datasets: [
                        {
                            label: 'Entradas',
                            data: data.history.inputs,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            fill: true,
                            tension: 0.4, // Curva suave como en la imagen
                            pointRadius: 0,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'Salidas',
                            data: data.history.outputs,
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.05)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'top', 
                            align: 'end',
                            labels: { usePointStyle: true, boxWidth: 6 } 
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9' } },
                        x: { border: { display: false }, grid: { display: false } }
                    }
                }
            });

            // 2. Stock por Almacén (Doughnut)
            new Chart(document.getElementById('chart-warehouses'), {
                type: 'doughnut',
                data: {
                    labels: data.distribution.labels,
                    datasets: [{
                        data: data.distribution.values,
                        backgroundColor: ['#6366F1', '#10B981', '#F59E0B', '#3B82F6', '#EC4899'],
                        hoverOffset: 10,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '45%', // Donut más delgado
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 20, boxWidth: 8 } 
                        }
                    }
                }
            });

            // 3. Top Productos (Horizontal Bar)
            new Chart(document.getElementById('chart-top-products'), {
                type: 'bar',
                data: {
                    labels: data.topProducts.labels,
                    datasets: [{
                        data: data.topProducts.values,
                        backgroundColor: '#818CF8', // Color violeta suave de la imagen
                        borderRadius: 8,
                        barThickness: 35
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: '#f1f5f9' }, border: { display: false } },
                        y: { grid: { display: false }, border: { display: false } }
                    }
                }
            });
        });
</script>