@extends('layouts.auth')

@section('content')
<div class="login-container">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="login-box">
            <div class="login-title">
                {{ __('Login') }}
            </div>

            {{-- CAMPO DE CORREO ELECTRÓNICO --}}
            <div class="input-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Correo Electrónico">
                <i class='bx bxs-user icon'></i>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert" style="color: white; display: block; margin-bottom: 15px;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            {{-- CAMPO DE CONTRASEÑA --}}
            <div class="input-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Contraseña">
                <i class='bx bx-show icon toggle-password'></i>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert" style="color: white; display: block; margin-bottom: 15px;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            {{-- OPCIONES DE RECORDAR Y OLVIDAR CONTRASEÑA --}}
            <div class="options">
                <label>
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    {{ __('Recuérdame') }}
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
            </div>

            {{-- BOTÓN DE INICIAR SESIÓN --}}
            <button type="submit" class="login-button">
                {{ __('Iniciar sesión') }}
            </button>

            {{-- ENLACE PARA REGISTRARSE --}}
            <div class="register-text">
                ¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate</a>
            </div>
        </div>
    </form>
</div>
@endsection