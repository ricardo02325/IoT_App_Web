@extends('layouts.app')

@section('title', 'Salones')

@section('content')
    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Salones</h1>
            </div>
            <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Descargar PDF</span>
            </a>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Salones</h3>
                    <i class='bx bx-search'></i>
                    <i class='bx bx-filter'></i>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Salón</th>
                            <th>Ubicación</th>
                            <th>Capacidad</th>
                            <th>Temperatura (°C)</th>
                            <th>Humedad (%)</th>
                            <th>Luminosidad (lux)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salones as $salon)
                                <tr>
                                    <td>{{ $salon->nombre }}</td>
                                    <td>{{ $salon->ubicacion }}</td>
                                    <td>{{ $salon->capacidad }}</td>
                                    <td>
                                        @php
                                            $temp = $salon->sensores->firstWhere('tipo', 'temperatura');
                                        @endphp
                                        {{ $temp->ultimaLectura->valor ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @php
                                            $hum = $salon->sensores->firstWhere('tipo', 'humedad');
                                        @endphp
                                        {{ $hum->ultimaLectura->valor ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @php
                                            $lum = $salon->sensores->firstWhere('tipo', 'luminosidad');
                                        @endphp
                                        {{ $lum->ultimaLectura->valor ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- /MAIN -->
    </section> {{-- cierre de <section id="content"> --}}
@endsection