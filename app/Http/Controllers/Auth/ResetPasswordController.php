<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/login';

    /**
     * Reglas de validación para restablecer la contraseña.
     * Aquí definimos la seguridad anti-inyección y complejidad.
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email', // 'email' evita inyecciones de scripts raros en el correo
            'password' => [
                'required',
                'confirmed',          // Valida que coincida con "Confirmar Contraseña"
                'min:8',              // Mínimo 8 caracteres
                'regex:/[a-z]/',      // Debe tener al menos una minúscula
                'regex:/[A-Z]/',      // Debe tener al menos una mayúscula
                'regex:/[0-9]/',      // Debe tener al menos un número
                'regex:/[@$!%*#?&,.]/', // Debe tener al menos un símbolo (evita caracteres "extraños" no permitidos)
            ],
        ];
    }

    protected function sendResetResponse(Request $request, $status)
    {
        Auth::logout();

        $message = trans($status); 

        return redirect('/login')->with('status', $message);
    }
}