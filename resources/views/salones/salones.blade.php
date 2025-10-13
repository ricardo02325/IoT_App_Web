@extends('layouts.app')

@section('title', 'FIE - Mapa Principal')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/salones.css') }}">
@endpush

@push('scripts')
    <!-- <script src="{{ asset('js/script.js') }}" defer></script> -->
@endpush

@section('content')
    <main class="body-seccion13">
    <div class="mapa-container"> 
        <img id="diagrama13" src="{{ asset('imgs/FIE.png') }}" alt="Mapa de la Facultad de Ingeniería Eléctrica">

        <div class="image-credit">
            Crédito del mapa: <a href="https://codepen.io/ramonfie-the-sasster/pen/bGyWzrO" target="_blank">[JIMENEZ BETANCOURT RAMON OCTAVIO]</a>
        </div>

        <!-- Marcadores interactivos -->
        <!-- <div class="circle13">
                    <span class="tooltip-text13">Laboratorio de Sistemas Eléctricos de Potencia (LSE)</span>
                </div>

                <div class="circle23">
                    <span class="tooltip-text13">Laboratorio de Electricidad y Magnetismo (LEM)</span>
                </div>

                <div class="circle33">
                    <span class="tooltip-text13">Laboratorio de Internet de las Cosas (LIOT)</span>
                </div>

                <div class="circle43">
                    <span class="tooltip-text13">Dirección (D)</span>
                </div>

                <div class="circle53">
                    <span class="tooltip-text13">Laboratorio de Mecánica (LM)</span>
                </div> -->

        <!-- 🔹 Salón 5D - Lecturas en tiempo real desde sensores -->
        <div class="circle63">
            <span class="tooltip-text13">
                Salón 5D - Edificio A
                <br>
                <strong>Lecturas en tiempo real:</strong>
                <br>Temperatura: <span id="live-temp">--</span> °C
                <br>Humedad: <span id="live-hum">--</span> %
                <br>Luminosidad: <span id="live-lux">--</span> lux
                <br><br>
                <strong>Límite de temperatura:</strong>
                <br>
                <input type="range" id="temp-slider" min="20" max="40" step="0.5" value="30" style="width: 120px;">
                <span id="temp-value">30.0 °C</span>
            </span>
        </div>

        <!-- <div class="circle73">
                    <span class="tooltip-text13">Aulas 2 (A2)</span>
                </div>

                <div class="circle83">
                    <span class="tooltip-text13">Aulas 3 (A3)</span>
                </div>

                <div class="circle93">
                    <span class="tooltip-text13">Laboratorio de Electrónica (LE)</span>
                </div>

                <div class="circle103">
                    <span class="tooltip-text13">Laboratorio de Instrumentación y Control (LIC)</span>
                </div> -->
    </main>
@endsection