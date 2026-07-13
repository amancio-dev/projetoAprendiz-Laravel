<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Aluno, Empresa, InstituicaoEducacao, Ponto};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'empresas'        => Empresa::count(),
            'alunos'          => Aluno::count(),
            'instituicoes'    => InstituicaoEducacao::count(),
            'pontos_pendentes' => Ponto::pendente()->count(),
        ];

        $pontos_recentes = Ponto::with(['aluno', 'empresa'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'pontos_recentes'));
    }
}
