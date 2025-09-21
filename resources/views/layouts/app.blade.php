<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">

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

	<!-- Mi JS -->
	<script src="{{ asset('js/admin/dashboard.js') }}"></script>
	@stack('js')
</body>
</html>