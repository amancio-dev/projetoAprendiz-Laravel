<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Mapeia o "tipo" vindo do formulário/URL para o broker de senha
     * configurado em config/auth.php (que por sua vez aponta pro provider certo).
     */
    private function brokerName(string $tipo): string
    {
        return match ($tipo) {
            'aluno'   => 'alunos',
            'empresa' => 'empresas',
            'admin'   => 'admins',
            default   => abort(404),
        };
    }

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'tipo'  => ['required', 'in:aluno,empresa,admin'],
        ]);

        $status = Password::broker($this->brokerName($request->tipo))
            ->sendResetLink($request->only('email'));

        // Mensagem genérica independente do resultado, para não revelar
        // se aquele e-mail existe ou não na base (evita enumeração de contas).
        return back()->with('success',
            'Se o e-mail informado existir em nossa base, um link de redefinição foi enviado.'
        );
    }

    public function showResetForm(Request $request, string $token)
    {
        $request->validate([
            'tipo'  => ['required', 'in:aluno,empresa,admin'],
            'email' => ['required', 'email'],
        ]);

        return view('auth.reset-password', [
            'token' => $token,
            'tipo'  => $request->tipo,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'tipo'     => ['required', 'in:aluno,empresa,admin'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $status = Password::broker($this->brokerName($request->tipo))->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => bcrypt($password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
