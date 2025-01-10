document.addEventListener('DOMContentLoaded', function() {
    // Função para buscar os dados do PHP
    fetch('includes/getBookingsData.php') // Caminho para o arquivo PHP
        .then(response => response.json())
        .then(data => {
            console.log('Dados recebidos:', data); // Log dos dados recebidos para depuração

            // Se não houver dados ou se os dados forem vazios, preenche com 0
            const bookingsData = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'].map(day => {
                // Encontra o booking_count para o dia da semana ou 0 caso não haja dados
                const dayData = data.find(item => item.day_of_week === day);
                return dayData ? dayData.booking_count : 0;
            });

            // Dados da semana, com a quantidade de marcações
            const daysOfWeek = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

            // Definir o limite de marcações por dia para 30
            const maxBookingsPerDay = 30;
            const warningLimit = 25; // Definido como 25 marcações para o aviso

            // Alterar a altura do gráfico para ser o dobro
            const chartContainer = document.getElementById('weeklyChart');
            chartContainer.height = 400; // Defina um valor maior de altura (ex: 400px)

            // Cor das barras com base nos limites
            const getBarColor = (count) => {
                if (count >= maxBookingsPerDay) {
                    return '#ff0000'; // Vermelho se ultrapassar 30 marcações
                } else if (count >= warningLimit) {
                    return '#ffcc00'; // Amarelo para aviso (acima de 25, mas abaixo de 30)
                } else {
                    return '#d4a373'; // Cor padrão
                }
            };

            // Preencher os cartões com as marcações
            const dailyBookings = bookingsData[0]; // Marcações do primeiro dia (Segunda)
            const weeklyBookings = bookingsData.reduce((sum, value) => sum + value, 0); // Soma das marcações da semana
            const monthlyBookings = bookingsData.reduce((sum, value) => sum + value, 0); // Pode ser alterado para dados do mês, se disponíveis

            // Atualizando o conteúdo dos cartões
            document.getElementById('dailyCount').textContent = `${dailyBookings}/${maxBookingsPerDay}`;
            document.getElementById('weeklyCount').textContent = `${weeklyBookings}/${maxBookingsPerDay * 6}`; // 6 dias da semana
            document.getElementById('monthlyCount').textContent = `${monthlyBookings}/${maxBookingsPerDay * 30}`; // Assumindo 30 dias no mês

            // Configuração do gráfico
            const ctx = chartContainer.getContext('2d');
            const weeklyChart = new Chart(ctx, {
                type: 'bar', // Tipo de gráfico: barras
                data: {
                    labels: daysOfWeek, // Dias da semana (exceto domingo)
                    datasets: [{
                        label: 'Número de Marcações',
                        data: bookingsData, // Dados de marcações ou 0 se não houver dados
                        backgroundColor: bookingsData.map(getBarColor), // Cor das barras dependendo do valor
                        borderColor: '#b5855f', // Cor das bordas das barras (mesma cor original)
                        borderWidth: 1,
                        hoverBackgroundColor: '#b5855f', // Cor ao passar o mouse (mesma cor original)
                        hoverBorderColor: '#8c4a2d', // Cor das bordas ao passar o mouse
                        borderRadius: 5, // Bordas arredondadas
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Garante que o gráfico se ajuste ao contêiner
                    aspectRatio: 2, // A altura será 2 vezes a largura
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 14,
                                    family: 'Arial, sans-serif',
                                    weight: 'bold',
                                    color: '#333'
                                }
                            },
                            grid: {
                                display: true,
                                color: '#ddd',
                                borderColor: '#ddd',
                                borderWidth: 1
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 14,
                                    family: 'Arial, sans-serif',
                                    weight: 'bold',
                                    color: '#333'
                                }
                            },
                            grid: {
                                display: false // Desativa as linhas de grade no eixo X
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#ddd',
                            borderWidth: 1,
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `Marcações: ${tooltipItem.raw}`;
                                }
                            }
                        },
                        legend: {
                            position: 'top', // Coloca a legenda na parte superior
                            labels: {
                                font: {
                                    size: 16,
                                    family: 'Arial, sans-serif',
                                    weight: 'bold',
                                    color: '#444'
                                },
                                padding: 20
                            }
                        }
                    },
                    animation: {
                        duration: 1000, // Duração da animação
                        easing: 'easeOutQuart', // Tipo de animação
                    }
                }
            });
        })
        .catch(error => {
            console.error('Erro ao carregar os dados do gráfico:', error);
            // Em caso de erro, exibe o gráfico com valores padrão
            const chartContainer = document.getElementById('weeklyChart');
            chartContainer.height = 400; // Ajuste a altura do gráfico mesmo em caso de erro

            const ctx = chartContainer.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                    datasets: [{
                        label: 'Número de Marcações',
                        data: [0, 0, 0, 0, 0, 0], // Valores padrão
                        backgroundColor: '#d4a373', // Cor das barras (mesma cor original)
                        borderColor: '#b5855f', // Cor das bordas das barras (mesma cor original)
                        borderWidth: 1,
                        hoverBackgroundColor: '#b5855f', // Cor ao passar o mouse
                        hoverBorderColor: '#8c4a2d', // Cor das bordas ao passar o mouse
                        borderRadius: 5, // Bordas arredondadas
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: 2, // A altura será 2 vezes a largura
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            ticks: {}
                        }
                    }
                }
            });
        });
});
