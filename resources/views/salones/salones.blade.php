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
            'LEM' => ['x' => 460, 'y' => 150],
            'LIOT' => ['x' => 848, 'y' => 150],
            'Dirección' => ['x' => 600, 'y' => 80],
            'LM' => ['x' => 198, 'y' => 287],
            '5D' => ['x' => 690, 'y' => 300],
            'A2' => ['x' => 850, 'y' => 260],
            'A3' => ['x' => 850, 'y' => 367],
            'LE' => ['x' => 587, 'y' => 150],
            'LIC' => ['x' => 670, 'y' => 150],
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
