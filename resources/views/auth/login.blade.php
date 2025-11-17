@extends('layouts.auth')

@section('content')
<div class="login-container">
    {{-- La etiqueta <form> ahora apunta a la ruta de login de Laravel y usa el método POST --}}
    <form method="POST" action="{{ route('login') }}">
        {{-- ¡MUY IMPORTANTE! Token de seguridad para proteger tu formulario --}}
        @csrf

        <div class="login-box">
            {{-- =============================================== --}}
            {{-- ==      TITULO DEL LOGIN (LO QUE FALTABA)    == --}}
            {{-- =============================================== --}}
            <div class="login-title">
                {{ __('Login') }}
            </div>
            {{-- =============================================== --}}

            {{-- Aquí va el mensaje de éxito/error, si lo quieres --}}
            @if (session('status'))
                <div id="status-alert" style="padding: 15px; background-color: #28a745; color: white; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold; transition: opacity 0.5s ease-out;">
                    {{ session('status') }}
                </div>
            @endif
            {{-- Fin del mensaje --}}

            {{-- CAMPO DE CORREO ELECTRÓNICO --}}
                <div class="input-group">
                    <input type="email" name="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                    <i class='bx bxs-user icon'></i>
                </div>
            {{-- Bloque para mostrar errores de validación para el campo email --}}
            @error('email')
                <span class="invalid-feedback" role="alert" style="color: white; display: block; margin-bottom: 15px;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            {{-- CAMPO DE CONTRASEÑA --}}
                    <div class="input-group">
                        <input id="password" type="password" name="password" placeholder="Contraseña" required>
                        <i class='bx bx-show icon toggle-password'></i>
                    </div>
             {{-- Bloque para mostrar errores de validación para el campo password --}}
            @error('password')
                <span class="invalid-feedback" role="alert" style="color: white; display: block; margin-bottom: 15px;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            
            {{-- =============================================== --}}
            {{-- ==      ENLACE DE RECUPERAR CONTRASEÑA       == --}}
            {{-- =============================================== --}}
            <div style="text-align: right; margin-bottom: 15px; width: 100%;">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: white; text-decoration: none; font-size: 0.9em;">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            {{-- =============================================== --}}


            {{-- Se asegura que el botón sea de tipo "submit" para enviar el formulario --}}
            <button type="submit" class="login-button">{{ __('Iniciar sesión') }}</button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const alert = document.getElementById('status-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500); // Coincide con la duración de la transición
            }, 8000); // 3 segundos antes de desaparecer
        }
    });
</script>
@endsection