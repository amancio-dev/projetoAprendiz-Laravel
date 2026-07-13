<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Ponto;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $empresa = Auth::guard('empresa')->user();

        $stats = [
            'alunos'           => $empresa->alunos()->count(),
            'instituicoes'     => $empresa->instituicoes()->count(),
            'pontos_pendentes' => $empresa->pontos()->pendente()->count(),
            'pontos_aprovados' => $empresa->pontos()->aprovado()->count(),
        ];

        $pontos_recentes = $empresa->pontos()
            ->with('aluno')
            ->latest()
            ->take(8)
            ->get();

        return view('empresa.dashboard', compact('stats', 'pontos_recentes', 'empresa'));
    }
}
