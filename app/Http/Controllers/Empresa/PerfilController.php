<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};

class PerfilController extends Controller
{
    /**
     * Exibe o formulário de edição do perfil da própria empresa logada.
     * Equivalente a gerenciar_empresa_logada.php / editar_empresa_logada.php no projeto original.
     */
    public function edit()
    {
        $empresa = Auth::guard('empresa')->user();

        return view('empresa.perfil.edit', compact('empresa'));
    }

    /**
     * Atualiza os dados da própria empresa.
     * Diferente do original (alt_empresa_logada.php), o id da empresa NUNCA
     * vem do formulário/GET — é sempre o usuário autenticado no guard 'empresa'.
     * Isso evita o tipo de falha de IDOR presente no PHP original, onde
     * qualquer id_empresa podia ser enviado via GET sem checar a sessão.
     */
    public function update(Request $request)
    {
        $empresa = Auth::guard('empresa')->user();

        $request->validate([
            'nome'  => ['required', 'string', 'max:60'],
            'cnpj'  => ['required', 'string', 'max:30', "unique:empresas,cnpj,{$empresa->id}"],
            'email' => ['required', 'email', 'max:100', "unique:empresas,email,{$empresa->id}"],
        ]);

        $data = $request->only(['nome', 'cnpj', 'email']);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:6', 'confirmed'],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $empresa->update($data);

        return redirect()->route('empresa.perfil.edit')
            ->with('success', 'Dados da empresa atualizados com sucesso!');
    }
}
