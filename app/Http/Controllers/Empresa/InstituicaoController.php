<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\InstituicaoEducacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstituicaoController extends Controller
{
    private function empresa()
    {
        return Auth::guard('empresa')->user();
    }

    public function index()
    {
        $instituicoes = $this->empresa()
            ->instituicoes()
            ->withCount('alunos')
            ->orderBy('nome')
            ->paginate(15);

        return view('empresa.instituicoes.index', compact('instituicoes'));
    }

    public function create()
    {
        return view('empresa.instituicoes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'      => ['required', 'string', 'max:60'],
            'cnpj'      => ['required', 'string', 'max:30'],
            'cod_turma' => ['nullable', 'string', 'max:30'],
        ]);

        $this->empresa()->instituicoes()->create(
            $request->only(['nome', 'cnpj', 'cod_turma'])
        );

        return redirect()->route('empresa.instituicoes.index')
            ->with('success', 'Instituição cadastrada com sucesso!');
    }

    public function edit(InstituicaoEducacao $instituicao)
    {
        abort_if($instituicao->empresa_id !== $this->empresa()->id, 403);
        return view('empresa.instituicoes.edit', compact('instituicao'));
    }

    public function update(Request $request, InstituicaoEducacao $instituicao)
    {
        abort_if($instituicao->empresa_id !== $this->empresa()->id, 403);

        $request->validate([
            'nome'      => ['required', 'string', 'max:60'],
            'cnpj'      => ['required', 'string', 'max:30'],
            'cod_turma' => ['nullable', 'string', 'max:30'],
        ]);

        $instituicao->update($request->only(['nome', 'cnpj', 'cod_turma']));

        return redirect()->route('empresa.instituicoes.index')
            ->with('success', 'Instituição atualizada com sucesso!');
    }

    public function destroy(InstituicaoEducacao $instituicao)
    {
        abort_if($instituicao->empresa_id !== $this->empresa()->id, 403);
        $instituicao->delete();
        return redirect()->route('empresa.instituicoes.index')
            ->with('success', 'Instituição removida com sucesso!');
    }
}
