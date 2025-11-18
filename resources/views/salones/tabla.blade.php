@extends('layouts.app')

@section('title', 'FIE - Tabla de Salones')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
<style>
    .no-data { color: #888; font-style: italic; }
    .options-btn { cursor: pointer; color: #0d6efd; }
    .options-btn.delete { color: #dc3545; }
    .options-btn:hover { opacity: 0.8; }
    .modal-header.bg-primary { background-color: #0d6efd !important; }
    .modal-header.bg-danger { background-color: #dc3545 !important; }
    .btn-primary { background-color: #0d6efd; border-color: #0d6efd; }
    .btn-primary:hover { background-color: #0b5ed7; border-color: #0a58ca; }
    .btn-danger { background-color: #dc3545; border-color: #dc3545; }
    .btn-danger:hover { background-color: #bb2d3b; border-color: #b02a37; }
    .form-label { font-weight: 500; }
    .is-invalid { border-color: #dc3545; }
    .invalid-feedback { display: block; color: #dc3545; font-size: 0.875em; }
    .alert ul { margin-bottom: 0; }
</style>
@endpush

@section('content')
@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center page-title mb-0">🏫 Tabla de Salones y Sensores</h2>
        <button class="btn btn-primary rounded-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#addSalonModal" title="Agregar nuevo salón">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <strong>Por favor, corrige los siguientes errores:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="toolbar mb-3">
        <input id="tableSearch" class="search-input" type="search" placeholder="Buscar por salón, sensor o ubicación...">
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
                                <span class="options-btn" data-bs-toggle="modal" data-bs-target="#editSalonModal{{ $salon->id_salon ?? $salon->id }}" title="Editar salón">
                                    <i class="bi bi-pencil-square"></i>
                                </span>
                                <span class="options-btn delete ms-2" data-bs-toggle="modal" data-bs-target="#deleteSalonModal{{ $salon->id_salon ?? $salon->id }}" title="Eliminar salón">
                                    <i class="bi bi-trash"></i>
                                </span>
                            </td>
                        </tr>

                        <!-- Modal editar salón -->
                        <div class="modal fade" id="editSalonModal{{ $salon->id_salon ?? $salon->id }}" tabindex="-1" aria-labelledby="editSalonLabel{{ $salon->id_salon ?? $salon->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('salones.update', $salon->id_salon ?? $salon->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">✏️ Editar {{ $salon->nombre }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre del salón</label>
                                                <input type="text" name="nombre" class="form-control @error('nombre', "edit{$salon->id_salon}") is-invalid @enderror" value="{{ old('nombre', $salon->nombre) }}" required>
                                                @error('nombre', "edit{$salon->id_salon}")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ubicación</label>
                                                <select name="ubicacion" class="form-control @error('ubicacion', "edit{$salon->id_salon}") is-invalid @enderror" required>
                                                    <option value="5D" {{ old('ubicacion', $salon->ubicacion) == '5D' ? 'selected' : '' }}>Salón 5D - Edificio A</option>
                                                    <option value="LSE" {{ old('ubicacion', $salon->ubicacion) == 'LSE' ? 'selected' : '' }}>Laboratorio LSE</option>
                                                    <option value="LEM" {{ old('ubicacion', $salon->ubicacion) == 'LEM' ? 'selected' : '' }}>Laboratorio LEM</option>
                                                    <option value="LIOT" {{ old('ubicacion', $salon->ubicacion) == 'LIOT' ? 'selected' : '' }}>Laboratorio LIOT</option>
                                                    <option value="D" {{ old('ubicacion', $salon->ubicacion) == 'D' ? 'selected' : '' }}>Dirección</option>
                                                    <option value="LM" {{ old('ubicacion', $salon->ubicacion) == 'LM' ? 'selected' : '' }}>Laboratorio LM</option>
                                                </select>
                                                @error('ubicacion', "edit{$salon->id_salon}")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">ID del dispositivo Particle</label>
                                                <input type="text" name="particle_id" class="form-control particle-input @error('particle_id', "edit{$salon->id_salon}") is-invalid @enderror" value="{{ old('particle_id', $dispositivo?->device_id ?? '') }}" data-salon-id="{{ $salon->id_salon ?? $salon->id }}">
                                                @error('particle_id', "edit{$salon->id_salon}")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal eliminar salón -->
                        <div class="modal fade" id="deleteSalonModal{{ $salon->id_salon ?? $salon->id }}" tabindex="-1" aria-labelledby="deleteSalonLabel{{ $salon->id_salon ?? $salon->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('salones.destroy', $salon->id_salon ?? $salon->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">🗑️ Eliminar Salón</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que deseas eliminar el salón <strong>"{{ $salon->nombre }}"</strong>?</p>
                                            <p class="text-danger mb-0">
                                                <i class="bi bi-exclamation-triangle-fill"></i>
                                                <strong>Advertencia:</strong> Esta acción no se puede deshacer y se eliminarán todos los datos asociados al salón.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Eliminar Salón</button>
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
        <form id="addSalonForm" method="POST" action="{{ route('salones.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addSalonLabel">➕ Agregar nuevo salón</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreSalon" class="form-label">Nombre del salón</label>
                        <input type="text" id="nombreSalon" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="ubicacionSalon" class="form-label">Ubicación</label>
                        <select id="ubicacionSalon" name="ubicacion" class="form-control @error('ubicacion') is-invalid @enderror" required>
                            <option value="">Selecciona un área</option>
                            <option value="5D" {{ old('ubicacion') == '5D' ? 'selected' : '' }}>Salón 5D - Edificio A</option>
                            <option value="LSE" {{ old('ubicacion') == 'LSE' ? 'selected' : '' }}>Laboratorio LSE</option>
                            <option value="LEM" {{ old('ubicacion') == 'LEM' ? 'selected' : '' }}>Laboratorio LEM</option>
                            <option value="LIOT" {{ old('ubicacion') == 'LIOT' ? 'selected' : '' }}>Laboratorio LIOT</option>
                            <option value="D" {{ old('ubicacion') == 'D' ? 'selected' : '' }}>Dirección</option>
                            <option value="LM" {{ old('ubicacion') == 'LM' ? 'selected' : '' }}>Laboratorio LM</option>
                        </select>
                        @error('ubicacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="particleID" class="form-label">ID del dispositivo Particle</label>
                        <input type="text" id="particleID" name="device_id" class="form-control particle-input @error('device_id') is-invalid @enderror" value="{{ old('device_id') }}" required>
                        @error('device_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="guardarSalonBtn">Guardar salón</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal de advertencia para ID duplicado -->
<div class="modal fade" id="duplicateParticleModal" tabindex="-1" aria-labelledby="duplicateParticleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="duplicateParticleLabel">⚠️ ID de Particle Duplicado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>El ID del dispositivo Particle <strong id="duplicateParticleId"></strong> ya está registrado en el sistema.</p>
                <p>Por favor, ingrese un ID diferente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
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

    // Recolecta device ids existentes desde la tabla
    function getExistingDevices() {
        const devices = new Set();
        const rows = Array.from(table.tBodies[0].rows);
        rows.forEach(r => {
            let deviceCell = r.cells[3]?.textContent?.trim();
            if (deviceCell) {
                deviceCell = deviceCell.toLowerCase();
                if (deviceCell && deviceCell !== 'no asignado') devices.add(deviceCell);
            }
        });
        return devices;
    }

    // Referencias
    const particleInput = document.getElementById('particleID');
    const duplicateParticleModal = new bootstrap.Modal(document.getElementById('duplicateParticleModal'));
    const duplicateParticleId = document.getElementById('duplicateParticleId');

    // Validación en tiempo real para ID de Particle en AGREGAR
    if (particleInput) {
        particleInput.addEventListener('blur', function() {
            const val = this.value.trim();
            if (val) {
                checkDeviceId(val, function(exists) {
                    if (exists) {
                        showDuplicateModal(val);
                    }
                });
            }
        });
    }

    // Validación en tiempo real para ID de Particle en EDITAR
    document.querySelectorAll('.particle-input').forEach(input => {
        input.addEventListener('blur', function() {
            const val = this.value.trim();
            if (val) {
                checkDeviceId(val, function(exists) {
                    if (exists) {
                        showDuplicateModal(val);
                    }
                });
            }
        });
    });

    // Función para verificar device_id via AJAX
    function checkDeviceId(deviceId, callback) {
        fetch('{{ route("check.device.id") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ device_id: deviceId })
        })
        .then(response => response.json())
        .then(data => {
            callback(data.exists);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Función para mostrar el modal de duplicado
    function showDuplicateModal(deviceId) {
        duplicateParticleId.textContent = deviceId;
        duplicateParticleModal.show();
    }

    // Mostrar modal automáticamente si hay error de duplicado del backend
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('show_duplicate_modal'))
            @if(old('device_id'))
                showDuplicateModal('{{ old("device_id") }}');
            @elseif(old('particle_id'))
                showDuplicateModal('{{ old("particle_id") }}');
            @endif
        @endif

        // Reabrir el modal correspondiente si hay errores
        @if($errors->any())
            @if($errors->has('device_id') || $errors->has('nombre') || $errors->has('ubicacion'))
                const addModal = new bootstrap.Modal(document.getElementById('addSalonModal'));
                addModal.show();
            @endif
        @endif
    });

    // Limpiar formulario cuando se cierra el modal de agregar
    const addSalonModalEl = document.getElementById('addSalonModal');
    if (addSalonModalEl) {
        addSalonModalEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('addSalonForm').reset();
        });
    }
</script>
@endpush
@endsection