@extends('layouts.auth')

@section('content')
<div class="login-container">
    {{-- La etiqueta <form> ahora apunta a la ruta de login de Laravel y usa el método POST --}}
    <form method="POST" action="{{ route('login') }}">
        {{-- ¡MUY IMPORTANTE! Token de seguridad para proteger tu formulario --}}
        @csrf

        <div class="login-box">
            <div class="login-title">
                {{-- La función __() es para traducciones, mostrará "Login" --}}
                {{ __('Login') }}
            </div>

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

            
            {{-- Se asegura que el botón sea de tipo "submit" para enviar el formulario --}}
            <button type="submit" class="login-button">{{ __('Iniciar sesión') }}</button>
        </div>
    </form>
</div>
@endsection