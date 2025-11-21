@extends('layouts.app')

@section('title', 'FIE - Tabla de Salones')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
    <style>
        /* Estilos generales */
        .no-data {
            color: #888;
            font-style: italic;
        }

        .options-btn {
            cursor: pointer;
            color: #0d6efd;
        }

        .modal-header.bg-primary {
            background-color: #0d6efd !important;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .form-label {
            font-weight: 500;
        }

        #nombre-error,
        #particle-error,
        #ubicacion-error {
            color: red;
            font-size: 0.875em;
            display: none;
        }
    </style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center page-title mb-0">🏫 Tabla de Salones y Sensores</h2>
            <button class="btn btn-primary rounded-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#addSalonModal"
                title="Agregar nuevo salón">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>

        <div class="toolbar mb-3">
            <input id="tableSearch" class="search-input" type="search"
                placeholder="Buscar por salón, sensor o ubicación...">
            <select id="typeFilter" class="filter-select">
                <option value="">Filtrar por tipo (Todos)</option>
                <option value="temperatura">Temperatura</option>
                <option value="humedad">Humedad</option>
                <option value="luz">Luz</option>
                <option value="gas">Gas</option>
            </select>
            <button id="clearFilters" class="btn btn-light">Limpiar</button>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table id="salonesTable" class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del Salón</th>
                            <th>Ubicación</th>
                            <th>Dispositivo Particle</th>
                            <th>Tipo</th>
                            <th>Última Lectura</th>
                            <th>Fecha / Hora</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salones as $index => $salon)
                            @php
                                $dispositivo = $salon->dispositivoParticle;
                                $lecturaTemp = $dispositivo?->sensores->where('tipo', 'temperatura')->first()?->lecturas->first();
                                $lecturaHum = $dispositivo?->sensores->where('tipo', 'humedad')->first()?->lecturas->first();
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $salon->nombre }}</strong></td>
                                <td>{{ $salon->ubicacion ?? 'Sin ubicación' }}</td>
                                <td>{{ $dispositivo?->device_id ?? 'No asignado' }}</td>
                                <td>Temperatura / Humedad</td>
                                <td>
                                    @if ($lecturaTemp)
                                        Temp: {{ number_format($lecturaTemp->valor, 2) }}°C
                                    @else
                                        <span class="no-data">No hay lectura temp</span>
                                    @endif
                                    <br>
                                    @if ($lecturaHum)
                                        Hum: {{ number_format($lecturaHum->valor, 2) }}%
                                    @else
                                        <span class="no-data">No hay lectura hum</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($lecturaTemp || $lecturaHum)
                                        {{ \Carbon\Carbon::parse($lecturaTemp?->fecha_hora ?? $lecturaHum?->fecha_hora)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="no-data">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="options-btn" data-bs-toggle="modal"
                                        data-bs-target="#editSalonModal{{ $salon->id }}" title="Editar salón">
                                        <i class="bi bi-pencil-square"></i>
                                    </span>
                                </td>
                            </tr>

                            <!-- Modal editar salón + dispositivo -->
                            <div class="modal fade" id="editSalonModal{{ $salon->id }}" tabindex="-1"
                                aria-labelledby="editSalonLabel{{ $salon->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('salones.update', $salon->id_salon ?? $salon->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">✏️ Editar {{ $salon->nombre }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                    aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nombre del salón</label>
                                                    <input type="text" name="nombre" class="form-control"
                                                        value="{{ $salon->nombre }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ubicación</label>
                                                    <select name="ubicacion" class="form-control" required>
                                                        <option value="5D" {{ $salon->ubicacion == '5D' ? 'selected' : '' }}>Salón
                                                            5D - Edificio A</option>
                                                        <option value="LSE" {{ $salon->ubicacion == 'LSE' ? 'selected' : '' }}>
                                                            Laboratorio LSE</option>
                                                        <option value="LEM" {{ $salon->ubicacion == 'LEM' ? 'selected' : '' }}>
                                                            Laboratorio LEM</option>
                                                        <option value="LIOT" {{ $salon->ubicacion == 'LIOT' ? 'selected' : '' }}>
                                                            Laboratorio LIOT</option>
                                                        <option value="D" {{ $salon->ubicacion == 'D' ? 'selected' : '' }}>
                                                            Dirección</option>
                                                        <option value="LM" {{ $salon->ubicacion == 'LM' ? 'selected' : '' }}>
                                                            Laboratorio LM</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">ID del dispositivo Particle</label>
                                                    <input type="text" name="particle_id" class="form-control"
                                                        value="{{ $dispositivo?->device_id ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal agregar nuevo salón -->
    <div class="modal fade" id="addSalonModal" tabindex="-1" aria-labelledby="addSalonLabel" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Usamos route() de Laravel para enviar el formulario -->
            <form id="addSalonForm" method="POST" action="{{ route('salones.store') }}">
                @csrf <!-- Token CSRF necesario -->
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addSalonLabel">➕ Agregar nuevo salón</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombreSalon" class="form-label">Nombre del salón</label>
                            <input type="text" id="nombreSalon" name="nombre" class="form-control" required>
                            <div id="nombre-error" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="ubicacionSalon" class="form-label">Ubicación</label>
                            <select id="ubicacionSalon" name="ubicacion" class="form-control" required>
                                <option value="">Selecciona un área</option>
                                <option value="5D">Salón 5D - Edificio A</option>
                                <option value="LSE">Laboratorio LSE</option>
                                <option value="LEM">Laboratorio LEM</option>
                                <option value="LIOT">Laboratorio LIOT</option>
                                <option value="D">Dirección</option>
                                <option value="LM">Laboratorio LM</option>
                            </select>
                            <div id="ubicacion-error" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="particleID" class="form-label">ID del dispositivo Particle</label>
                            <input type="text" id="particleID" name="device_id" class="form-control" required>
                            <div id="particle-error" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="guardarSalonBtn">Guardar salón</button>
                    </div>
                </div>
            </form>
        </div>
        <div id="status"></div>
    </div>

    @push('scripts')
        <script>
            // Búsqueda y filtro
            const table = document.getElementById('salonesTable');
            const searchInput = document.getElementById('tableSearch');
            const typeFilter = document.getElementById('typeFilter');
            const clearBtn = document.getElementById('clearFilters');

            searchInput.addEventListener('input', function () {
                const filter = searchInput.value.toLowerCase();
                Array.from(table.tBodies[0].rows).forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
                });
            });

            typeFilter.addEventListener('change', function () {
                const value = typeFilter.value.toLowerCase();
                Array.from(table.tBodies[0].rows).forEach(row => {
                    if (!value) row.style.display = '';
                    else row.style.display = row.cells[4].textContent.toLowerCase().includes(value) ? '' : 'none';
                });
            });

            clearBtn.addEventListener('click', function () {
                searchInput.value = '';
                typeFilter.value = '';
                Array.from(table.tBodies[0].rows).forEach(row => row.style.display = '');
            });

            // Recolecta nombres de salones y device ids existentes desde la tabla
            function getExistingSets() {
                const names = new Set();
                const devices = new Set();
                const rows = Array.from(table.tBodies[0].rows);
                rows.forEach(r => {
                    // nombres pueden venir con <strong>, textContent ya limpia eso
                    const nameCell = r.cells[1]?.textContent?.trim();
                    let deviceCell = r.cells[3]?.textContent?.trim();
                    if (nameCell) {
                        const n = nameCell.toLowerCase();
                        if (n) names.add(n);
                    }
                    if (deviceCell) {
                        deviceCell = deviceCell.toLowerCase();
                        // ignorar marcadores como "no asignado" u otras cadenas vacías
                        if (deviceCell && deviceCell !== 'no asignado') devices.add(deviceCell);
                    }
                });
                return { names, devices };
            }

            // Referencias al formulario y campos del modal
            const nombreInput = document.getElementById('nombreSalon');
            const particleInput = document.getElementById('particleID');
            const guardarBtn = document.getElementById('guardarSalonBtn');
            const nombreError = document.getElementById('nombre-error');
            const particleError = document.getElementById('particle-error');

            // Estado de existencia
            let existsName = false;
            let existsDevice = false;

            // Función para actualizar el estado del botón guardar
            function updateGuardarState() {
                const ubicacionInput = document.getElementById('ubicacionSalon');
                const hasEmpty = !nombreInput.value.trim() || !particleInput.value.trim() || !ubicacionInput.value.trim();
                guardarBtn.disabled = hasEmpty || existsName || existsDevice;
            }

            // Validación en tiempo real para nombre del salón
            nombreInput.addEventListener('input', function () {
                const val = nombreInput.value.trim().toLowerCase();
                const { names } = getExistingSets();
                if (val && names.has(val)) {
                    nombreError.textContent = 'Ya está';
                    nombreError.style.display = 'block';
                    existsName = true;
                } else {
                    nombreError.textContent = '';
                    nombreError.style.display = 'none';
                    existsName = false;
                }
                updateGuardarState();
            });

            // Validación en tiempo real para ID de Particle
            particleInput.addEventListener('input', function () {
                const val = particleInput.value.trim().toLowerCase();
                const { devices } = getExistingSets();
                if (val && devices.has(val)) {
                    particleError.textContent = 'Ya está';
                    particleError.style.display = 'block';
                    existsDevice = true;
                } else {
                    particleError.textContent = '';
                    particleError.style.display = 'none';
                    existsDevice = false;
                }
                updateGuardarState();
            });

            // Asegurar estado correcto cuando se abre el modal (recalcular sets y validar valores actuales)
            const addSalonModalEl = document.getElementById('addSalonModal');
            addSalonModalEl.addEventListener('show.bs.modal', function () {
                // limpiar mensajes previos
                nombreError.textContent = '';
                nombreError.style.display = 'none';
                particleError.textContent = '';
                particleError.style.display = 'none';
                existsName = false;
                existsDevice = false;

                // recalcular y validar valores actuales de los inputs (por si quedaron prefijados)
                const { names, devices } = getExistingSets();
                const nombreVal = nombreInput.value.trim().toLowerCase();
                const particleVal = particleInput.value.trim().toLowerCase();

                if (nombreVal && names.has(nombreVal)) {
                    nombreError.textContent = 'Ya está';
                    nombreError.style.display = 'block';
                    existsName = true;
                }
                if (particleVal && devices.has(particleVal)) {
                    particleError.textContent = 'Ya está';
                    particleError.style.display = 'block';
                    existsDevice = true;
                }

                updateGuardarState();
            });

            // Validación del formulario de agregar salón (con verificación de existencia)
            const addForm = document.getElementById('addSalonForm');
            addForm.addEventListener('submit', function (e) {
                let valid = true;
                ['nombre', 'ubicacion', 'particle_id'].forEach(field => {
                    const input = document.getElementById(field === 'particle_id' ? 'particleID' : field + 'Salon');
                    const errorDiv = document.getElementById(field + '-error');
                    if (!input.value.trim()) {
                        errorDiv.textContent = 'Este campo es obligatorio';
                        errorDiv.style.display = 'block';
                        valid = false;
                    } else {
                        if (errorDiv.textContent === 'Ya está') {
                            // mantener mensaje de existencia
                        } else {
                            errorDiv.textContent = '';
                            errorDiv.style.display = 'none';
                        }
                    }
                });

                // Recalcular por seguridad antes de enviar
                const { names, devices } = getExistingSets();
                if (nombreInput.value.trim() && names.has(nombreInput.value.trim().toLowerCase())) {
                    nombreError.textContent = 'Ya está';
                    nombreError.style.display = 'block';
                    existsName = true;
                    valid = false;
                }
                if (particleInput.value.trim() && devices.has(particleInput.value.trim().toLowerCase())) {
                    particleError.textContent = 'Ya está';
                    particleError.style.display = 'block';
                    existsDevice = true;
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                    updateGuardarState();
                }
            });
        </script>
    @endpush
@endsection