@extends('layouts.auth')

@section('content')
<div class="login-container">
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="login-box">
            <div class="login-title">
                {{ __('Confirmar Contraseña') }}
            </div>

            <div style="color: white; font-size: 0.9em; text-align: center; margin-bottom: 20px;">
                Por seguridad, por favor confirma tu contraseña para continuar.
            </div>

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

            {{-- BOTÓN DE CONFIRMAR --}}
            <button type="submit" class="login-button">
                {{ __('Confirmar Contraseña') }}
            </button>

            {{-- ENLACE PARA OLVIDAR CONTRASEÑA --}}
            <div class="register-text" style="text-align: center; margin-top: 20px;">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: white; text-decoration: none; font-size: 0.9em;">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection