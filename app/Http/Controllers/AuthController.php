<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Redireciona se já estiver autenticado
        if (Auth::guard('web')->check())     return redirect()->route('admin.dashboard');
        if (Auth::guard('empresa')->check()) return redirect()->route('empresa.dashboard');
        if (Auth::guard('aluno')->check())   return redirect()->route('aluno.dashboard');

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'tipo'     => ['required', 'in:1,2,3'],
        ], [
            'email.required'    => 'Informe o e-mail.',
            'email.email'       => 'E-mail inválido.',
            'password.required' => 'Informe a senha.',
            'tipo.required'     => 'Selecione o tipo de usuário.',
        ]);

        $tipo = (int) $request->tipo;

        [$guard, $redirect] = match ($tipo) {
            1 => ['aluno',   route('aluno.dashboard')],
            2 => ['empresa', route('empresa.dashboard')],
            3 => ['web',     route('admin.dashboard')],
        };

        if (Auth::guard($guard)->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->intended($redirect);
        }

        return back()
            ->withInput($request->only('email', 'tipo'))
            ->withErrors(['email' => 'E-mail ou senha inválidos.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('empresa')->logout();
        Auth::guard('aluno')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
