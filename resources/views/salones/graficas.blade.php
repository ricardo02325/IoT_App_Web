@extends('layouts.app')

@section('title', 'FIE - Gráficas de Sensores')

@push('css')
<link rel="stylesheet" href="{{ asset('css/salones.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --warning-color: #f72585;
        --info-color: #4895ef;
        --dark-color: #2b2d42;
        --light-color: #f8f9fa;
        --card-bg: rgba(255, 255, 255, 0.95);
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }

    .header-container {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 0 0 20px 20px;
        padding: 2rem 0;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
        color: white;
    }

    .card {
        border: none;
        border-radius: 20px;
        box-shadow: var(--shadow);
        margin-bottom: 2rem;
        background: var(--card-bg);
        transition: var(--transition);
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border-radius: 20px 20px 0 0 !important;
        padding: 1.2rem 1.5rem;
        border-bottom: none;
    }

    .card-body {
        padding: 1.5rem;
    }

    canvas {
        max-height: 350px;
        width: 100% !important;
    }

    .stats-card {
        text-align: center;
        padding: 1.5rem;
    }

    .stats-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }

    .stats-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .chart-title {
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-title i {
        font-size: 1.5rem;
    }

    .last-update {
        font-size: 0.8rem;
        color: #6c757d;
        text-align: right;
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .card {
            margin-bottom: 1.5rem;
        }
        
        .header-container {
            padding: 1.5rem 0;
        }
    }
</style>
@endpush

@section('content')
<div class="header-container">
    <div class="container">
        <h1 class="text-center mb-3"><i class="fas fa-chart-line me-2"></i>Gráficas de Sensores</h1>
        <p class="text-center mb-0">Monitoreo en tiempo real de los sensores de la FIE</p>
    </div>
</div>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <div class="stats-value text-primary">{{ round($promedios['temperatura'] ?? 0, 1) }}°C</div>
                <div class="stats-label">Temperatura Promedio</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="stats-value text-info">{{ round($promedios['humedad'] ?? 0, 1) }}%</div>
                <div class="stats-label">Humedad Promedio</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="stats-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stats-value text-success">{{ count($fechas) ?? 0 }}</div>
                <div class="stats-label">Total de Mediciones</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-thermometer-half me-2"></i>Temperatura (°C)</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartTemperatura"></canvas>
                    <div class="last-update">Última actualización: {{ $fechas[count($fechas)-1] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tint me-2"></i>Humedad (%)</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartHumedad"></canvas>
                    <div class="last-update">Última actualización: {{ $fechas[count($fechas)-1] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Promedios de Sensores</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartPromedios"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const fechas = @json($fechas);
    const temperatura = @json($temperatura);
    const humedad = @json($humedad);
    const promedios = @json($promedios);

    // Crear gradientes para las gráficas
    function createGradient(ctx, color1, color2) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    }

    // Temperatura - Gráfica de línea con gradiente
    const tempCtx = document.getElementById('chartTemperatura').getContext('2d');
    const tempGradient = createGradient(tempCtx, 'rgba(255, 99, 132, 0.6)', 'rgba(255, 99, 132, 0.1)');
    
    new Chart(tempCtx, {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: 'Temperatura (°C)',
                data: temperatura,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: tempGradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Humedad - Gráfica de línea con gradiente
    const humCtx = document.getElementById('chartHumedad').getContext('2d');
    const humGradient = createGradient(humCtx, 'rgba(54, 162, 235, 0.6)', 'rgba(54, 162, 235, 0.1)');
    
    new Chart(humCtx, {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: 'Humedad (%)',
                data: humedad,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: humGradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(54, 162, 235)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Promedios - Gráfica de doughnut con efecto 3D
    new Chart(document.getElementById('chartPromedios'), {
        type: 'doughnut',
        data: {
            labels: ['Temperatura', 'Humedad'],
            datasets: [{
                data: [promedios.temperatura, promedios.humedad],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)'
                ],
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 13
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                if (context.label === 'Temperatura') {
                                    label += context.parsed.toFixed(1) + '°C';
                                } else if (context.label === 'Humedad') {
                                    label += context.parsed.toFixed(1) + '%';
                                }
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush