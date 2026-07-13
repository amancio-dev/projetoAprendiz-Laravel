<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Ponto;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $aluno = Auth::guard('aluno')->user();

        $stats = [
            'total'     => $aluno->pontos()->count(),
            'aprovados' => $aluno->pontos()->aprovado()->count(),
            'pendentes' => $aluno->pontos()->pendente()->count(),
        ];

        $pontos_recentes = $aluno->pontos()
            ->latest()
            ->take(8)
            ->get();

        return view('aluno.dashboard', compact('aluno', 'stats', 'pontos_recentes'));
    }
}
