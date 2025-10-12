<!-- SIDEBAR -->
<section id="sidebar">
	<a href="#" class="brand">
		<i class='bx bxs-smile'></i>
		<span class="text">Panel de administrador</span>
	</a>
	<ul class="side-menu top">
		<li class="{{ request()->routeIs('inicio') ? 'active' : '' }}">
			<a href="{{ route('inicio') }}">
				<i class='bx bxs-dashboard'></i>
				<span class="text">Dashboard</span>
			</a>
		</li>
		<li class="{{ request()->routeIs('salones') ? 'active' : '' }}">
			<a href="{{ route('salones') }}">
				<i class='bx bxs-school'></i>
				<span class="text">Salones</span>
			</a>
		</li>
		<li>
			<!-- <a href="#">
				<i class='bx bxs-doughnut-chart'></i>
				<span class="text">Estadisticas</span>
			</a> -->
		</li>
	</ul>
	<ul class="side-menu">
	<li>
    <a href="{{ route('logout') }}" class="logout" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class='bx bxs-log-out-circle'></i>
        <span class="text">Logout</span>
    </a>
</li>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
	</ul>
</section>
<!-- /SIDEBAR -->

<!-- NAVBAR -->
<section id="content">
	<nav>
		<i class='bx bx-menu'></i>
		<a href="#" class="notification">
			<i class='bx bxs-bell'></i>
		</a>
		<a href="#" class="profile">
			<img src="https://i.pinimg.com/474x/c3/14/99/c31499032ea434ddec72571e4e476647.jpg">
		</a>
	</nav>