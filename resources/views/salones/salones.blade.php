@extends('layouts.app')

@section('title', 'FIE - Mapa Principal')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/salones.css') }}">
@endpush

@section('content')

    @php
        // Coordenadas de los salones en el mapa
        $posiciones = [
            'LSE' => ['x' => 200, 'y' => 350],
            'LEM' => ['x' => 460, 'y' => 300],
            'A1' => ['x' => 690, 'y' => 480],
            'Dirección' => ['x' => 600, 'y' => 150],
            'LM' => ['x' => 198, 'y' => 460],
            '5D' => ['x' => 848, 'y' => 300],
            'A2' => ['x' => 880, 'y' => 450],
            'A3' => ['x' => 880, 'y' => 560],
            'LE' => ['x' => 587, 'y' => 300],
            'LIC' => ['x' => 670, 'y' => 300],
        ];
    @endphp

    {{-- Contenedor con data-attributes para JS --}}
    <div id="mapa-container" data-salones='@json($salones, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)'
        data-sensores='@json($sensores, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)'
        data-dispositivos='@json($dispositivosParticle, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)'>

        <main class="body-seccion13">

            <img id="diagrama13" src="{{ asset('imgs/FIE.png') }}" alt="Mapa FIE">

            @foreach($salones as $salon)
                    @php 
                        $ubicacion = $salon->ubicacion; 
                    @endphp

                @if(isset($posiciones[$ubicacion]))
                       <div class="map-marker"
                             id="marker-{{ $salon->id_salon }}"
                             style="top: {{ $posiciones[$ubicacion]['y'] }}px; left: {{ $posiciones[$ubicacion]['x'] }}px;">
                            <div class="circle"></div>

                            <span class="tooltip">
                                {{ $salon->nombre }} <br>
                                ({{ $salon->ubicacion }})
                                <br><br>
                                Temp: <span id="temp-{{ $salon->id_salon }}">--</span> °C<br>
                                Hum: <span id="hum-{{ $salon->id_salon }}">--</span> %
                                </span>
                                </div>
                @endif
            @endforeach

        </main>
    </div>

@endsection
