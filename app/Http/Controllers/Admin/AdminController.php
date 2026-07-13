<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};

class AdminController extends Controller
{
    /**
     * Equivalente a gerenciar_usuario.php no projeto original:
     * lista os administradores (dono_app) e permite cadastrar novos.
     */
    public function index()
    {
        $admins = Admin::orderBy('nome')->paginate(15);

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'     => ['required', 'string', 'max:60'],
            'email'    => ['required', 'email', 'max:100', 'unique:admins'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        Admin::create([
            'nome'     => $request->nome,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrador cadastrado com sucesso!');
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nome'  => ['required', 'string', 'max:60'],
            'email' => ['required', 'email', 'max:100', "unique:admins,email,{$admin->id}"],
        ]);

        $data = $request->only(['nome', 'email']);

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:6', 'confirmed']]);
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrador atualizado com sucesso!');
    }

    /**
     * Remove um administrador.
     * Duas proteções que o projeto original não tinha:
     * 1. Um admin não pode excluir a própria conta (evita ficar trancado fora).
     * 2. Não é possível excluir o último administrador do sistema.
     */
    public function destroy(Admin $admin)
    {
        if ($admin->id === Auth::guard('web')->id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        if (Admin::count() <= 1) {
            return back()->with('error', 'Não é possível excluir o único administrador do sistema.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrador removido com sucesso!');
    }
}
