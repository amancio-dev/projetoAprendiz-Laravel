<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Aluno, Empresa, InstituicaoEducacao};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlunoController extends Controller
{
    public function index()
    {
        $alunos = Aluno::with(['empresa', 'instituicao'])
            ->orderBy('nome')
            ->paginate(15);

        return view('admin.alunos.index', compact('alunos'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('nome')->get();
        return view('admin.alunos.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'          => ['required', 'string', 'max:60'],
            'cpf'           => ['nullable', 'string', 'max:30'],
            'email'         => ['required', 'email', 'max:100', 'unique:alunos'],
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
            'empresa_id'    => ['required', 'exists:empresas,id'],
            'instituicao_id'=> ['required', 'exists:instituicoes_educacao,id'],
        ]);

        Aluno::create([
            'nome'           => $request->nome,
            'cpf'            => $request->cpf,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'empresa_id'     => $request->empresa_id,
            'instituicao_id' => $request->instituicao_id,
        ]);

        return redirect()->route('admin.alunos.index')
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function edit(Aluno $aluno)
    {
        $empresas    = Empresa::orderBy('nome')->get();
        $instituicoes = InstituicaoEducacao::where('empresa_id', $aluno->empresa_id)
            ->orderBy('nome')->get();

        return view('admin.alunos.edit', compact('aluno', 'empresas', 'instituicoes'));
    }

    public function update(Request $request, Aluno $aluno)
    {
        $request->validate([
            'nome'           => ['required', 'string', 'max:60'],
            'cpf'            => ['nullable', 'string', 'max:30'],
            'email'          => ['required', 'email', 'max:100', "unique:alunos,email,{$aluno->id}"],
            'empresa_id'     => ['required', 'exists:empresas,id'],
            'instituicao_id' => ['required', 'exists:instituicoes_educacao,id'],
        ]);

        $data = $request->only(['nome', 'cpf', 'email', 'empresa_id', 'instituicao_id']);

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:6', 'confirmed']]);
            $data['password'] = Hash::make($request->password);
        }

        $aluno->update($data);

        return redirect()->route('admin.alunos.index')
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy(Aluno $aluno)
    {
        $aluno->delete();
        return redirect()->route('admin.alunos.index')
            ->with('success', 'Aluno removido com sucesso!');
    }

    /**
     * API: retorna instituições por empresa (para select dinâmico via JS)
     */
    public function instituicoesPorEmpresa(Empresa $empresa)
    {
        return response()->json(
            $empresa->instituicoes()->orderBy('nome')->get(['id', 'nome', 'cod_turma'])
        );
    }
}
