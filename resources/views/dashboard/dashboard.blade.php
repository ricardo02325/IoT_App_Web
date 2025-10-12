@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
	<!-- MAIN -->
	<main>
		<div class="head-title">
			<div class="left">
				<h1>Dashboard</h1>
			</div>
			<!-- <a href="#" class="btn-download">
												<i class='bx bxs-cloud-download'></i>
												<span class="text">Descargar PDF</span>
											</a> -->
		</div>

		<ul class="box-info">
			<li>
				<i class='bx bxs-thermometer'></i>
				<span class="text">
					<h3>{{ $valorMaximo ?? '28' }}</h3>
					<p>Temperatura máxima</p>
				</span>
			</li>
			<li>
				<i class='bx bxs-group'></i>
				<span class="text">
					<h3>{{ $totalAlumnos ?? '0' }}</h3>
					<p>Total de alumnos</p>
				</span>
			</li>
			<li>
				<i class='bx bxs-school'></i>
				<span class="text">
					<h3>{{ $totalSalones ?? '0' }}</h3>
					<p>Total de salones</p>
				</span>
			</li>
		</ul>

		<div class="table-data">
			<div class="order">
				<div class="head">
					<h3>Ordenes recibidas</h3>
					<i class='bx bx-search'></i>
					<i class='bx bx-filter'></i>
				</div>
				<table>
					<thead>
						<tr>
							<th style="text-align: center;">Título</th>
							<th style="text-align: center;">Descripción</th>
							<th style="text-align: center;">Salón</th>
							<th style="text-align: center;">Última Lectura</th>
							<th style="text-align: center;">Estado</th>
							<th style="text-align: center;">Fecha</th>
						</tr>
					</thead>
					<tbody>
						@php
							// Agrupar reportes por salón y obtener el último de cada grupo
							$ultimosReportes = $reportes->groupBy('id_salon')->map(function ($grupo) {
								return $grupo->sortByDesc('created_at')->first();
							});
						@endphp

						@forelse($ultimosReportes as $reporte)
										<tr>
											<td style="text-align: center; vertical-align: middle; padding: 40px 0;">
												{{ $reporte->titulo }}
											</td>
											<td style="text-align: center;">{{ $reporte->descripcion }}</td>
											<td style="text-align: center;">{{ $reporte->salon->nombre ?? 'N/A' }}</td>
											<td style="text-align: center;">
												@if($reporte->salon && $reporte->salon->sensores->count())
													@php
														$ultimaTemp = null;
														$ultimaHum = null;
														$ultimaLux = null;

														foreach ($reporte->salon->sensores as $sensor) {
															$tipo = strtolower($sensor->tipo ?? '');
															$lectura = $sensor->lecturas->sortByDesc('fecha_hora')->first();
															$valor = $lectura->valor ?? null;

															if (str_contains($tipo, 'temperatura')) {
																$ultimaTemp = $valor;
															} elseif (str_contains($tipo, 'humedad')) {
																$ultimaHum = $valor;
															} elseif (str_contains($tipo, 'luz') || str_contains($tipo, 'luminosidad')) {
																$ultimaLux = $valor;
															}
														}
													@endphp

													<strong>Temperatura:</strong> {{ $ultimaTemp ?? 'N/A' }} °C<br>
													<strong>Humedad:</strong> {{ $ultimaHum ?? 'N/A' }} %<br>
													<strong>Luminosidad:</strong> {{ $ultimaLux ?? 'N/A' }} lx
												@else
													N/A
												@endif
											</td>
											<td style="text-align: center;">
												<span class="status 
										{{ $reporte->estatus == 'completado' ? 'completed' :
							($reporte->estatus == 'pendiente' ? 'pending' : 'process') }}">
													{{ ucfirst($reporte->estatus) }}
												</span>
											</td>
											<td style="text-align: center;">{{ $reporte->created_at->format('d/m/Y H:i') }}</td>
										</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center">No hay reportes disponibles</td>
							</tr>
						@endforelse
					</tbody>
					<!-- CIERRO LA TABLA -->
				</table>
			</div>

			<!-- <div class="todo">
															<div class="head">
																<h3>Todos</h3>
																<i class='bx bx-plus'></i>
																<i class='bx bx-filter'></i>
															</div>
															<ul class="todo-list">
																<li class="completed">
																	<p>Todo List</p>
																	<i class='bx bx-dots-vertical-rounded'></i>
																</li>
																<li class="not-completed">
																	<p>Todo List</p>
																	<i class='bx bx-dots-vertical-rounded'></i>
																</li>
															</ul>
												</div> -->
		</div>
	</main>
	<!-- /MAIN -->
	</section> {{-- cierro el <section id="content"> que abrimos en barra --}}
@endsection