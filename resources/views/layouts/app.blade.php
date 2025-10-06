<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Mi CSS -->
	<link rel="stylesheet" href="{{ asset('css/index.css') }}">

	@stack('css')
	<title>@yield('title', 'Panel de administrador')</title>
</head>
<body>
	{{-- Sidebar y Navbar --}}
	@include('partials.barra')

	{{-- Contenido dinámico --}}
	@yield('content')

	<!-- jQuery -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

	<!-- Particle JS externo -->
	<script src="https://unpkg.com/particle-api-js/dist/particle.min.js"></script>

	<!-- Mi JS -->
	<script src="{{ asset('js/admin/dashboard.js') }}"></script>
	<script src="{{ asset('js/admin/particle.js') }}"></script>

	@stack('js')
</body>
</html>