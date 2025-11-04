@extends('layouts.app')

@section('title', 'FIE - Gráficas de Sensores')

@push('css')
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> -->
<link rel="stylesheet" href="{{ asset('css/salones.css') }}">
<style>
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    canvas {
        max-height: 350px;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">📊 Gráficas de Sensores</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="text-center mb-3">Temperatura (°C)</h5>
                <canvas id="chartTemperatura"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="text-center mb-3">Humedad (%)</h5>
                <canvas id="chartHumedad"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="text-center mb-3">Luminosidad (lux)</h5>
                <canvas id="chartLuminosidad"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="text-center mb-3">Promedios de Sensores</h5>
                <canvas id="chartPromedios"></canvas>
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
    const luminosidad = @json($luminosidad);
    const promedios = @json($promedios);

    // Temperatura
    new Chart(document.getElementById('chartTemperatura'), {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: 'Temperatura',
                data: temperatura,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255,99,132,0.3)',
                fill: true,
                tension: 0.3
            }]
        }
    });

    // Humedad
    new Chart(document.getElementById('chartHumedad'), {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: 'Humedad',
                data: humedad,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54,162,235,0.3)',
                fill: true,
                tension: 0.3
            }]
        }
    });

    // Luminosidad
    new Chart(document.getElementById('chartLuminosidad'), {
        type: 'bar',
        data: {
            labels: fechas,
            datasets: [{
                label: 'Luminosidad',
                data: luminosidad,
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                borderColor: 'rgb(255, 206, 86)',
                borderWidth: 1
            }]
        }
    });

    // Promedios
    new Chart(document.getElementById('chartPromedios'), {
        type: 'doughnut',
        data: {
            labels: ['Temperatura', 'Humedad', 'Luminosidad'],
            datasets: [{
                data: [promedios.temperatura, promedios.humedad, promedios.luminosidad],
                backgroundColor: [
                    'rgba(255,99,132,0.6)',
                    'rgba(54,162,235,0.6)',
                    'rgba(255,206,86,0.6)'
                ],
                borderWidth: 1
            }]
        }
    });
});
</script>
@endpush