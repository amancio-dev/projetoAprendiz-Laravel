<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::withCount(['alunos', 'instituicoes'])
            ->orderBy('nome')
            ->paginate(15);

        return view('admin.empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('admin.empresas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'     => ['required', 'string', 'max:60'],
            'cnpj'     => ['required', 'string', 'max:30', 'unique:empresas'],
            'email'    => ['required', 'email', 'max:100', 'unique:empresas'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        Empresa::create([
            'nome'     => $request->nome,
            'cnpj'     => $request->cnpj,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'admin_id' => Auth::guard('web')->id(),
        ]);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa cadastrada com sucesso!');
    }

    public function show(Empresa $empresa)
    {
        $empresa->load(['alunos', 'instituicoes', 'pontos' => fn($q) => $q->latest()->take(10)]);
        return view('admin.empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa)
    {
        return view('admin.empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nome'  => ['required', 'string', 'max:60'],
            'cnpj'  => ['required', 'string', 'max:30', "unique:empresas,cnpj,{$empresa->id}"],
            'email' => ['required', 'email', 'max:100', "unique:empresas,email,{$empresa->id}"],
        ]);

        $data = $request->only(['nome', 'cnpj', 'email']);

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:6', 'confirmed']]);
            $data['password'] = Hash::make($request->password);
        }

        $empresa->update($data);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa removida com sucesso!');
    }
}
