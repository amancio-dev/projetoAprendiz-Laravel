<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Ponto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PontoController extends Controller
{
    private function aluno()
    {
        return Auth::guard('aluno')->user();
    }

    public function registrar()
    {
        $aluno = $this->aluno()->load(['empresa', 'instituicao']);
        return view('aluno.ponto.registrar', compact('aluno'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'localizacao' => ['nullable', 'string', 'max:255'],
        ]);

        $aluno = $this->aluno()->load(['empresa', 'instituicao']);

        Ponto::create([
            'aluno_id'        => $aluno->id,
            'empresa_id'      => $aluno->empresa_id,
            'nome_aluno'      => $aluno->nome,
            'nome_empresa'    => $aluno->empresa->nome ?? 'N/A',
            'nome_instituicao'=> $aluno->instituicao->nome ?? 'N/A',
            'localizacao'     => $request->localizacao,
            'status'          => Ponto::STATUS_PENDENTE,
        ]);

        return redirect()->route('aluno.ponto.historico')
            ->with('success', 'Ponto registrado! Aguardando aprovação da empresa.');
    }

    public function historico(Request $request)
    {
        $query = $this->aluno()->pontos()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pontos = $query->paginate(20)->withQueryString();

        return view('aluno.ponto.historico', compact('pontos'));
    }
}
