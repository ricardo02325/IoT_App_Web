@extends('layouts.auth')

@section('content')
<div class="login-container">
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="login-box">
            <div class="login-title">
                {{ __('Recuperar Contraseña') }}
            </div>

            {{-- =============================================== --}}
            {{-- ==  1. DIV CON ID Y TRANSICIÓN AÑADIDOS      == --}}
            {{-- =============================================== --}}
            @if (session('status'))
                <div id="status-alert" style="padding: 15px; background-color: #28a745; color: white; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold; transition: opacity 0.5s ease-out;">
                    {{ session('status') }}
                </div>
            @endif
            {{-- =============================================== --}}


            <div style="color: white; font-size: 0.9em; text-align: center; margin-bottom: 20px;">
                Ingresa tu correo y te enviaremos un enlace para reestablecer tu contraseña.
            </div>

            {{-- CAMPO DE CORREO ELECTRÓNICO --}}
            <div class="input-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Correo Electrónico">
                <i class='bx bxs-envelope icon'></i>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert" style="color: white; display: block; margin-bottom: 15px;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            {{-- BOTÓN DE ENVIAR ENLACE --}}
            <button type="submit" class="login-button">
                {{ __('Enviar enlace') }}
            </button>

            {{-- ENLACE PARA VOLVER A INICIAR SESIÓN --}}
            <div class="register-text" style="text-align: center; margin-top: 20px;">
                <a href="{{ route('login') }}" style="color: white; text-decoration: none; font-size: 0.9em;">Volver a Iniciar Sesión</a>
            </div>
        </div>
    </form>
</div>

{{-- =============================================== --}}
{{-- ==      2. SCRIPT DE ANIMACIÓN AÑADIDO       == --}}
{{-- =============================================== --}}
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        
        // 1. Busca la alerta por su ID
        const alert = document.getElementById('status-alert');

        // 2. Si la alerta existe...
        if (alert) {
            
            // 3. Espera 3 segundos (3000ms)
            setTimeout(() => {
                
                // 4. Inicia la animación de desvanecimiento
                alert.style.opacity = '0';
                
                // 5. Espera a que termine la animación (0.5s = 500ms)
                setTimeout(() => {
                    // 6. Oculta el elemento completamente
                    alert.style.display = 'none';
                }, 500); // Coincide con la duración de la transición

            }, 6000); // 3 segundos antes de desaparecer
        }
    });
</script>
@endsection