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

    // Reset Legend
    const legendContainer = document.getElementById('chartLegend');
    if (legendContainer) legendContainer.innerHTML = '';

    const ctx = document.getElementById('weeklyChart').getContext('2d');

    // Mostrar loading
    chartLoading.style.display = 'flex';
    chartError.style.display = 'none';
    chartNoData.style.display = 'none';

    // Ajustar a URL com base no período
    let url = 'index.php?route=api/dashboard-chart';
    if (period) {
        url += `&period=${period}`;
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
                    // Loose equality used intentionally as day is number from DB and label is string
                    const dayData = data.find(item => item.day_of_month == day);
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

            // Create Gradient Function
            const createGradient = (ctx, value, max) => {
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                const ratio = value / max;

                let colorStart, colorEnd;

                if (ratio < 0.33) {
                    // Low - Green/Teal (Premium Emerald)
                    colorStart = '#10b981';
                    colorEnd = 'rgba(16, 185, 129, 0.2)';
                } else if (ratio < 0.66) {
                    // Medium - Amber/Orange
                    colorStart = '#f59e0b';
                    colorEnd = 'rgba(245, 158, 11, 0.2)';
                } else {
                    // High - Rose/Red
                    colorStart = '#ef4444';
                    colorEnd = 'rgba(239, 68, 68, 0.2)';
                }

                gradient.addColorStop(0, colorStart);
                gradient.addColorStop(1, colorEnd);
                return gradient;
            };

            // Determine Current Day Index for highlighting
            let currentDayIndex = -1;
            const now = new Date();

            if (period === 'weekly') {
                // Adjust for Monday start (0=Sun, 1=Mon, ..., 6=Sat)
                const day = now.getDay(); // 0=Sun, 1=Mon...
                // Our labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
                // Mon(1)->0, Tue(2)->1, Wed(3)->2, Thu(4)->3, Fri(5)->4, Sat(6)->5
                if (day >= 1 && day <= 6) {
                    currentDayIndex = day - 1;
                }
            } else if (period === 'monthly') {
                // Labels: 1, 2, 3...
                // Index: date - 1
                currentDayIndex = now.getDate() - 1;
            }

            // Custom configuration for a premium look
            Chart.defaults.font.family = "'Outfit', sans-serif";
            Chart.defaults.color = '#a0a0a0';

            // Destroy existing chart if it exists
            if (chartInstance) {
                chartInstance.destroy();
            }

            // Create Chart
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Número de Marcações',
                        data: bookingsData,
                        backgroundColor: function (context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) {
                                return null;
                            }
                            const value = context.raw;
                            // Ensure we pass a sensible max if maxBookingsPerDay is 0 to avoid division by zero
                            return createGradient(ctx, value, maxBookingsPerDay || 10);
                        },
                        borderWidth: function (context) {
                            return context.dataIndex === currentDayIndex ? 2 : 0;
                        },
                        borderColor: function (context) {
                            return context.dataIndex === currentDayIndex ? '#ffffff' : 'transparent';
                        },
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.5,
                        categoryPercentage: 0.8,
                        hoverBackgroundColor: '#fff' // Highlight on hover
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(30, 30, 30, 0.95)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            titleFont: { size: 13, weight: '600' },
                            bodyFont: { size: 13 },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    return context.parsed.y + ' Marcações';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: Math.max(...bookingsData, maxBookingsPerDay),
                            grid: {
                                color: 'rgba(255, 255, 255, 0.03)',
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#666',
                                padding: 10,
                                font: { size: 11 }
                            },
                            border: { display: false }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#888',
                                padding: 10,
                                font: { size: 11 }
                            },
                            border: { display: false }
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Garante que a legenda personalizada apareça
            setTimeout(() => {
                const legendContainer = document.getElementById('chartLegend');
                if (legendContainer) {
                    legendContainer.innerHTML = `
                        <div class="chart-custom-legend">
                            <div class="legend-item">
                                <span class="legend-dot low"></span>
                                <span>Tranquilo</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot medium"></span>
                                <span>Moderado</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot high"></span>
                                <span>Intenso</span>
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

// Função para atualizar a dashboard com base no filtro de período
function updateDashboard() {
    const period = document.getElementById('periodFilter').value;

    // Atualizar Gráfico
    loadChartData(period);

    // Atualizar Cards de Estatísticas
    // Nota: dailyData, weeklyData, monthlyData devem ser definidos na view (dashboard.php)
    let data = weeklyData; // Default
    if (period === 'daily' && typeof dailyData !== 'undefined') data = dailyData;
    if (period === 'monthly' && typeof monthlyData !== 'undefined') data = monthlyData;

    if (data) {
        animateValue('val-total', parseInt(data.total_bookings || 0));
        animateValue('val-completed', parseInt(data.completed_bookings || 0));
        animateValue('val-pending', parseInt(data.pending_bookings || 0));
        animateValue('val-canceled', parseInt(data.canceled_bookings || 0));
    }
}

// Simple animation for numbers
function animateValue(id, end) {
    const obj = document.getElementById(id);
    if (!obj) return;

    const start = parseInt(obj.innerHTML) || 0;
    if (start === end) return;

    const range = end - start;
    const duration = 500;
    let startTime = null;

    function step(timestamp) {
        if (!startTime) startTime = timestamp;
        const progress = Math.min((timestamp - startTime) / duration, 1);
        obj.innerHTML = Math.floor(progress * range + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        } else {
            obj.innerHTML = end;
        }
    }
    window.requestAnimationFrame(step);
}

// Carregar o gráfico ao iniciar
document.addEventListener('DOMContentLoaded', function () {
    // Initial Load
    // We don't call updateDashboard() immediately to avoid re-setting the PHP rendered values, 
    // but we do need to load the chart.
    loadChartData('weekly');

    // Listener para o filtro
    const filter = document.getElementById('periodFilter');
    if (filter) {
        filter.addEventListener('change', updateDashboard);
    }

    // Listener para o botão de atualizar (se existir)
    const refreshBtn = document.querySelector('.filter-controls button');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function () {
            location.reload(); // Simples reload para pegar dados frescos do servidor
        });
    }
});