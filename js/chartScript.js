let chartInstance = null; // Variável global para armazenar o gráfico

function loadChartData(period = 'weekly') {
    console.log('Período selecionado para o gráfico:', period);
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
            let labels, bookingsData, maxBookingsPerDay;

            if (period === 'daily') {
                labels = ['00h-04h', '04h-08h', '08h-12h', '12h-16h', '16h-20h', '20h-24h'];
                maxBookingsPerDay = 10;
                bookingsData = labels.map(slot => {
                    const slotData = data.find(item => item.time_slot === slot);
                    return slotData ? slotData.booking_count : 0;
                });
            } else if (period === 'monthly') {
                const daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
                labels = Array.from({ length: daysInMonth }, (_, i) => (i + 1).toString());
                maxBookingsPerDay = 50;
                bookingsData = labels.map(day => {
                    const dayData = data.find(item => item.day === day);
                    return dayData ? dayData.booking_count : 0;
                });
            } else {
                labels = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
                maxBookingsPerDay = 30;
                bookingsData = labels.map(day => {
                    const dayData = data.find(item => item.day_of_week === day);
                    return dayData ? dayData.booking_count : 0;
                });
            }

            console.log('Labels:', labels);
            console.log('Dados do gráfico:', bookingsData);

            // Verificar se todos os dados estão zerados
            const allZero = bookingsData.every(val => val === 0);
            if (allZero) {
                console.warn('Todos os dados do gráfico estão zerados. Verifique se há dados na tabela marcacoes para o período selecionado.');
                chartLoading.style.display = 'none';
                chartNoData.style.display = 'flex';
                return;
            }

            // Função para obter a cor da barra com base no número de marcações
            const getBarColor = (count) => {
                const minColor = [0, 200, 0];   // Verde (Poucas marcações)
                const midColor = [255, 165, 0]; // Laranja (Médio)
                const maxColor = [255, 0, 0];   // Vermelho (Limite)
                const ratio = Math.min(count / maxBookingsPerDay, 1);
                let r, g, b;

                if (ratio < 0.5) {
                    r = Math.round(minColor[0] + ratio * 2 * (midColor[0] - minColor[0]));
                    g = Math.round(minColor[1] + ratio * 2 * (midColor[1] - minColor[1]));
                    b = Math.round(minColor[2] + ratio * 2 * (midColor[2] - minColor[2]));
                } else {
                    r = Math.round(midColor[0] + (ratio - 0.5) * 2 * (maxColor[0] - midColor[0]));
                    g = Math.round(midColor[1] + (ratio - 0.5) * 2 * (maxColor[1] - midColor[1]));
                    b = Math.round(midColor[2] + (ratio - 0.5) * 2 * (maxColor[2] - midColor[2]));
                }

                return `rgb(${r}, ${g}, ${b})`;
            };

            // Se já existir um gráfico, destrói-o antes de criar outro
            if (chartInstance !== null) {
                chartInstance.destroy();
            }

            // Cria o gráfico e armazena a instância
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Número de Marcações',
                        data: bookingsData,
                        backgroundColor: bookingsData.map(getBarColor),
                        borderColor: '#b5855f',
                        borderWidth: 1,
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: maxBookingsPerDay,
                            ticks: {
                                stepSize: 5
                            },
                            grid: {
                                color: '#ddd'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });

            // Garante que a legenda personalizada apareça
            setTimeout(() => {
                const legendContainer = document.getElementById('chartLegend');
                if (legendContainer) {
                    legendContainer.innerHTML = `
                        <div style="display: flex; justify-content: center; gap: 15px; margin-top: 10px; font-size: 14px;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 20px; height: 20px; background-color: rgb(0,200,0); margin-right: 5px;"></div>
                                <span>Poucas marcações</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div style="width: 20px; height: 20px; background-color: rgb(255,165,0); margin-right: 5px;"></div>
                                <span>Moderado</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div style="width: 20px; height: 20px; background-color: rgb(255,69,0); margin-right: 5px;"></div>
                                <span>Elevado</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div style="width: 20px; height: 20px; background-color: rgb(255,0,0); margin-right: 5px;"></div>
                                <span>No limite</span>
                            </div>
                        </div>
                    `;
                }
            }, 500);

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