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

    protected function sendResetResponse(Request $request, $status)
    {
        Auth::logout();

        $message = trans($status); 

        return redirect('/login')->with('status', $message);
    }
}