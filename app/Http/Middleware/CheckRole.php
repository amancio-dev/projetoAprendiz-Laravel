<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Verifica se o usuário está autenticado no guard correto.
     * Uso nas rotas: middleware('role:admin'), middleware('role:empresa'), middleware('role:aluno')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $guard = match ($role) {
            'admin'   => 'web',
            'empresa' => 'empresa',
            'aluno'   => 'aluno',
            default   => null,
        };

        if ($guard === null || ! Auth::guard($guard)->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Não autorizado.'], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Faça login para continuar.');
        }

        return $next($request);
    }
}
