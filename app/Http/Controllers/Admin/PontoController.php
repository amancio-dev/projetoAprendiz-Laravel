<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ponto;
use Illuminate\Http\Request;

class PontoController extends Controller
{
    public function index(Request $request)
    {
        $query = Ponto::with(['aluno', 'empresa'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        $pontos = $query->paginate(20)->withQueryString();

        return view('admin.pontos.index', compact('pontos'));
    }

    public function aprovar(Ponto $ponto)
    {
        $ponto->update(['status' => Ponto::STATUS_APROVADO]);
        return back()->with('success', 'Ponto aprovado!');
    }

    public function rejeitar(Ponto $ponto)
    {
        $ponto->update(['status' => Ponto::STATUS_REJEITADO]);
        return back()->with('success', 'Ponto rejeitado.');
    }
}
