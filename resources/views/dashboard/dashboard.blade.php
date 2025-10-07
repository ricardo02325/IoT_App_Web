@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
	<!-- MAIN -->
	<main>
		<div class="head-title">
			<div class="left">
				<h1>Dashboard</h1>
			</div>
			<a href="#" class="btn-download">
				<i class='bx bxs-cloud-download'></i>
				<span class="text">Descargar PDF</span>
			</a>
		</div>

		<ul class="box-info">
			<li>
				<i class='bx bxs-calendar-check'></i>
				<span class="text">
					<h3>{{ $valorMaximo ?? 'N/A' }}</h3>
					<p>Valor máximo</p>
				</span>
			</li>
			<li>
				<i class='bx bxs-group'></i>
				<span class="text">
					<h3>{{ $totalSalones ?? '0' }}</h3>
					<p>Alumnos</p>
				</span>
			</li>
			<li>
				<i class='bx bxs-dollar-circle'></i>
				<span class="text">
					<h3>{{ $totalAlumnos ?? '0' }}</h3>
					<p>De alumnos</p>
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
							<th>Título</th>
							<th>Descripción</th>
							<th>Salón</th>
							<th>Última Lectura</th>
							<th>Estado</th>
							<th>Fecha</th>
						</tr>
					</thead>
					<tbody>
						@forelse($reportes as $reporte)
							<tr>
								<td>{{ $reporte->titulo }}</td>
								<td>{{ $reporte->descripcion }}</td>
								<td>{{ $reporte->salon->nombre ?? 'N/A' }}</td>
								<td>
									@if($reporte->salon && $reporte->salon->sensores)
										@foreach($reporte->salon->sensores as $sensor)
											{{ ucfirst($sensor->tipo) }}:
											{{ $sensor->lecturas->first()->valor ?? 'N/A' }}
											<br>
										@endforeach
									@else
										N/A
									@endif
								</td>
								<td>
									<span
										class="status 
								{{ $reporte->estatus == 'completado' ? 'completed' : ($reporte->estatus == 'pendiente' ? 'pending' : 'process') }}">
										{{ ucfirst($reporte->estatus) }}
									</span>
								</td>
								<td>{{ $reporte->created_at }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center">No hay reportes disponibles</td>
							</tr>
						@endforelse
					</tbody>
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