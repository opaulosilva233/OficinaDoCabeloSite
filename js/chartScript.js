let chartInstance = null; // Variável global para armazenar o gráfico

function loadChartData(period = 'weekly') {
    const chartContainer = document.getElementById('chartContainer');
    const chartLoading = document.getElementById('chartLoading');
    const chartError = document.getElementById('chartError');
    const chartNoData = document.createElement('div');
    chartNoData.id = 'chartNoData';
    chartNoData.className = 'no-data';
    chartNoData.innerHTML = '<i class="fas fa-info-circle"></i> Sem dados para exibir.';
    chartContainer.appendChild(chartNoData);
    chartNoData.style.display = 'none';

    const ctx = document.getElementById('weeklyChart').getContext('2d');

    // Mostrar loading
    chartLoading.style.display = 'flex';
    chartError.style.display = 'none';
    chartNoData.style.display = 'none';

    // Ajustar a URL com base no período
    let url = 'includes/getBookingsData.php';
    if (period !== 'weekly') {
        url += `?period=${period}`;
    }
    console.log('Carregando dados do gráfico para URL:', url);

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na requisição: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Verificar se há erro na resposta
            if (data.error) {
                throw new Error(data.error);
            }

            console.log('Dados recebidos do servidor:', data);
            let labels, maxBookingsPerDay;
            if (period === 'daily') {
                labels = ['00h-04h', '04h-08h', '08h-12h', '12h-16h', '16h-20h', '20h-24h'];
                maxBookingsPerDay = 10;
            } else if (period === 'monthly') {
                labels = Array.from({ length: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate() }, (_, i) => (i + 1).toString());
                maxBookingsPerDay = 50;
            } else {
                labels = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
                maxBookingsPerDay = 30;
            }

            const marcadasData = labels.map(label => {
                let item;
                if (period === 'daily') {
                    item = data.find(d => d.time_slot === label);
                } else if (period === 'monthly') {
                    item = data.find(d => d.day === parseInt(label));
                } else {
                    item = data.find(d => d.day_of_week === label);
                }
                return item ? item.marcadas : 0;
            });

            const concluidasData = labels.map(label => {
                let item;
                if (period === 'daily') {
                    item = data.find(d => d.time_slot === label);
                } else if (period === 'monthly') {
                    item = data.find(d => d.day === parseInt(label));
                } else {
                    item = data.find(d => d.day_of_week === label);
                }
                return item ? item.concluidas : 0;
            });

            const canceladasData = labels.map(label => {
                let item;
                if (period === 'daily') {
                    item = data.find(d => d.time_slot === label);
                } else if (period === 'monthly') {
                    item = data.find(d => d.day === parseInt(label));
                } else {
                    item = data.find(d => d.day_of_week === label);
                }
                return item ? item.canceladas : 0;
            });

            console.log('Labels:', labels);
            console.log('Dados do gráfico - Marcadas:', marcadasData);
            console.log('Dados do gráfico - Concluídas:', concluidasData);
            console.log('Dados do gráfico - Canceladas:', canceladasData);

            // Verificar se todos os dados estão zerados
            const allZero = marcadasData.every(val => val === 0) && concluidasData.every(val => val === 0) && canceladasData.every(val => val === 0);
            if (allZero) {
                console.warn('Todos os dados do gráfico estão zerados. Verifique se há dados na tabela marcacoes para o período selecionado.');
                chartLoading.style.display = 'none';
                chartNoData.style.display = 'flex';
                return;
            }

            // Se já existir um gráfico, destrói-o antes de criar outro
            if (chartInstance !== null) {
                chartInstance.destroy();
            }

            // Cria o gráfico e armazena a instância
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Marcadas',
                            data: marcadasData,
                            backgroundColor: '#ff9800', // Laranja
                            borderColor: '#e68900',
                            borderWidth: 1,
                            borderRadius: 5,
                        },
                        {
                            label: 'Concluídas',
                            data: concluidasData,
                            backgroundColor: '#28a745', // Verde
                            borderColor: '#218838',
                            borderWidth: 1,
                            borderRadius: 5,
                        },
                        {
                            label: 'Canceladas',
                            data: canceladasData,
                            backgroundColor: '#dc3545', // Vermelho
                            borderColor: '#c82333',
                            borderWidth: 1,
                            borderRadius: 5,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            max: maxBookingsPerDay,
                            ticks: {
                                stepSize: 5
                            },
                            grid: {
                                color: '#ddd'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#2b2b2b',
                            titleColor: '#fff',
                            bodyColor: '#d4a373',
                            borderColor: '#d4a373',
                            borderWidth: 1,
                            cornerRadius: 5,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.parsed.y}`;
                                }
                            }
                        }
                    }
                }
            });

            // Esconder loading
            chartLoading.style.display = 'none';
        })
        .catch(error => {
            console.error('Ocorreu um erro ao carregar os dados do gráfico:', error);
            chartLoading.style.display = 'none';
            chartError.style.display = 'flex';
        });
}

// Função para exportar dados como CSV
function exportToCsv(filename, csvData) {
    const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Função para atualizar a dashboard com base no filtro de período
function updateDashboard() {
    const period = document.getElementById('periodFilter').value;
    const dailySummary = document.getElementById('dailySummary');
    const weeklySummary = document.getElementById('weeklySummary');
    const monthlySummary = document.getElementById('monthlySummary');

    dailySummary.style.display = period === 'daily' ? 'flex' : 'none';
    weeklySummary.style.display = period === 'weekly' ? 'flex' : 'none';
    monthlySummary.style.display = period === 'monthly' ? 'flex' : 'none';

    loadChartData(period);
}

// Carregar o gráfico ao iniciar
document.addEventListener('DOMContentLoaded', function () {
    loadChartData('weekly');

    // Atualizar ao clicar no botão "Atualizar"
    document.getElementById('refreshBtn').addEventListener('click', function() {
        updateDashboard();
    });
});