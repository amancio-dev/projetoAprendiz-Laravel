<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Empresa, InstituicaoEducacao};
use Illuminate\Http\Request;

class InstituicaoController extends Controller
{
    public function index()
    {
        $instituicoes = InstituicaoEducacao::with('empresa')
            ->withCount('alunos')
            ->orderBy('nome')
            ->paginate(15);

        return view('admin.instituicoes.index', compact('instituicoes'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('nome')->get();
        return view('admin.instituicoes.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'       => ['required', 'string', 'max:60'],
            'cnpj'       => ['required', 'string', 'max:30'],
            'cod_turma'  => ['nullable', 'string', 'max:30'],
            'empresa_id' => ['required', 'exists:empresas,id'],
        ]);

        InstituicaoEducacao::create($request->only(['nome', 'cnpj', 'cod_turma', 'empresa_id']));

        return redirect()->route('admin.instituicoes.index')
            ->with('success', 'Instituição cadastrada com sucesso!');
    }

    public function edit(InstituicaoEducacao $instituicao)
    {
        $empresas = Empresa::orderBy('nome')->get();
        return view('admin.instituicoes.edit', compact('instituicao', 'empresas'));
    }

    public function update(Request $request, InstituicaoEducacao $instituicao)
    {
        $request->validate([
            'nome'       => ['required', 'string', 'max:60'],
            'cnpj'       => ['required', 'string', 'max:30'],
            'cod_turma'  => ['nullable', 'string', 'max:30'],
            'empresa_id' => ['required', 'exists:empresas,id'],
        ]);

        $instituicao->update($request->only(['nome', 'cnpj', 'cod_turma', 'empresa_id']));

        return redirect()->route('admin.instituicoes.index')
            ->with('success', 'Instituição atualizada com sucesso!');
    }

    public function destroy(InstituicaoEducacao $instituicao)
    {
        $instituicao->delete();
        return redirect()->route('admin.instituicoes.index')
            ->with('success', 'Instituição removida com sucesso!');
    }
}
