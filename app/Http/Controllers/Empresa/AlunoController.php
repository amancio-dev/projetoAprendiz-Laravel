<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};

class AlunoController extends Controller
{
    private function empresa()
    {
        return Auth::guard('empresa')->user();
    }

    public function index()
    {
        $alunos = $this->empresa()
            ->alunos()
            ->with('instituicao')
            ->orderBy('nome')
            ->paginate(15);

        return view('empresa.alunos.index', compact('alunos'));
    }

    public function create()
    {
        $instituicoes = $this->empresa()->instituicoes()->orderBy('nome')->get();
        return view('empresa.alunos.create', compact('instituicoes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'           => ['required', 'string', 'max:60'],
            'cpf'            => ['nullable', 'string', 'max:30'],
            'email'          => ['required', 'email', 'max:100', 'unique:alunos'],
            'password'       => ['required', 'string', 'min:6', 'confirmed'],
            'instituicao_id' => ['required', 'exists:instituicoes_educacao,id'],
        ]);

        $this->empresa()->alunos()->create([
            'nome'           => $request->nome,
            'cpf'            => $request->cpf,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'instituicao_id' => $request->instituicao_id,
        ]);

        return redirect()->route('empresa.alunos.index')
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function edit(Aluno $aluno)
    {
        // Garante que o aluno pertence à empresa logada
        abort_if($aluno->empresa_id !== $this->empresa()->id, 403);

        $instituicoes = $this->empresa()->instituicoes()->orderBy('nome')->get();
        return view('empresa.alunos.edit', compact('aluno', 'instituicoes'));
    }

    public function update(Request $request, Aluno $aluno)
    {
        abort_if($aluno->empresa_id !== $this->empresa()->id, 403);

        $request->validate([
            'nome'           => ['required', 'string', 'max:60'],
            'cpf'            => ['nullable', 'string', 'max:30'],
            'email'          => ['required', 'email', 'max:100', "unique:alunos,email,{$aluno->id}"],
            'instituicao_id' => ['required', 'exists:instituicoes_educacao,id'],
        ]);

        $data = $request->only(['nome', 'cpf', 'email', 'instituicao_id']);

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:6', 'confirmed']]);
            $data['password'] = Hash::make($request->password);
        }

        $aluno->update($data);

        return redirect()->route('empresa.alunos.index')
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy(Aluno $aluno)
    {
        abort_if($aluno->empresa_id !== $this->empresa()->id, 403);
        $aluno->delete();
        return redirect()->route('empresa.alunos.index')
            ->with('success', 'Aluno removido com sucesso!');
    }
}
