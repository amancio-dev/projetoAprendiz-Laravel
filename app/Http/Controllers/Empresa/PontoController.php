<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Ponto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PontoController extends Controller
{
    private function empresa()
    {
        return Auth::guard('empresa')->user();
    }

    public function index(Request $request)
    {
        $query = $this->empresa()->pontos()->with('aluno')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('aluno_id')) {
            $query->where('aluno_id', $request->aluno_id);
        }

        $pontos = $query->paginate(20)->withQueryString();
        $alunos = $this->empresa()->alunos()->orderBy('nome')->get();

        return view('empresa.pontos.index', compact('pontos', 'alunos'));
    }

    public function aprovar(Ponto $ponto)
    {
        abort_if($ponto->empresa_id !== $this->empresa()->id, 403);
        $ponto->update(['status' => Ponto::STATUS_APROVADO]);
        return back()->with('success', 'Ponto aprovado com sucesso!');
    }

    public function rejeitar(Ponto $ponto)
    {
        abort_if($ponto->empresa_id !== $this->empresa()->id, 403);
        $ponto->update(['status' => Ponto::STATUS_REJEITADO]);
        return back()->with('warning', 'Ponto rejeitado.');
    }
}
