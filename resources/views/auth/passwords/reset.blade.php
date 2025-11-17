@extends('layouts.auth')

@section('content')
<div class="login-container">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        {{-- ¡IMPORTANTE! Este token oculto es necesario --}}
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="login-box">
            <div class="login-title">
                {{ __('Reestablecer Contraseña') }}
            </div>

            {{-- CAMPO DE CORREO (Viene del enlace) --}}
            <div class="input-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Correo Electrónico" readonly>
                <i class='bx bxs-envelope icon'></i>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert" style="color: white; display: block; margin-bottom: 15px;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            {{-- CAMPO DE NUEVA CONTRASEÑA --}}
            <div class="input-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Nueva Contraseña">
                <i class='bx bx-show icon toggle-password'></i>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert" style="color: white; display: block; margin-bottom: 15px;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            {{-- CAMPO DE CONFIRMAR NUEVA CONTRASEÑA --}}
            <div class="input-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar Nueva Contraseña">
                <i class='bx bx-show icon toggle-password'></i>
            </div>

            {{-- BOTÓN DE REESTABLECER --}}
            <button type="submit" class="login-button">
                {{ __('Reestablecer Contraseña') }}
            </button>
        </div>
    </form>
</div>
@endsection