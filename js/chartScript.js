let chartInstance = null; // Variável global para armazenar o gráfico

document.addEventListener('DOMContentLoaded', function () {
    fetch('includes/getBookingsData.php')
        .then(response => response.json())
        .then(data => {
            console.log('Dados recebidos:', data);
            const bookingsData = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'].map(day => {
                const dayData = data.find(item => item.day_of_week === day);
                return dayData ? dayData.booking_count : 0;
            });

            const daysOfWeek = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
            const maxBookingsPerDay = 30;

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

            const chartContainer = document.getElementById('weeklyChart');
            chartContainer.height = 400;
            const ctx = chartContainer.getContext('2d');

            // Se já existir um gráfico, destrói-o antes de criar outro
            if (chartInstance !== null) {
                chartInstance.destroy();
            }

            // Cria o gráfico e armazena a instância
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: daysOfWeek,
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
                    responsive: false, // Impede que o gráfico se redimensione automaticamente
                    maintainAspectRatio: false, // Impede que o gráfico altere sua proporção
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
                            display: true, // Mantém a legenda do Chart.js visível
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });

            // Garante que a legenda apareça
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
        })
        .catch(error => {
            console.error('Ocorreu um erro ao carregar os dados do gráfico:', error);
        });
});