@extends('layouts.app')

@section('title','Gerenciar Empresas')
@section('page-title','Empresas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Empresas</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-building mr-2"></i>Empresas cadastradas</h3>
        <div class="card-tools">
            <a href="{{ route('admin.empresas.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Nova Empresa
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover dt-table">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>E-mail</th>
                    <th class="text-center">Alunos</th>
                    <th class="text-center">Instituições</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empresas as $empresa)
                <tr>
                    <td>{{ $empresa->id }}</td>
                    <td><strong>{{ $empresa->nome }}</strong></td>
                    <td>{{ $empresa->cnpj }}</td>
                    <td>{{ $empresa->email }}</td>
                    <td class="text-center"><span class="badge badge-info">{{ $empresa->alunos_count }}</span></td>
                    <td class="text-center"><span class="badge badge-secondary">{{ $empresa->instituicoes_count }}</span></td>
                    <td class="text-center text-nowrap">
                        <a href="{{ route('admin.empresas.edit', $empresa) }}" class="btn btn-xs btn-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.empresas.destroy', $empresa) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Excluir a empresa {{ addslashes($empresa->nome) }}? Todos os dados relacionados serão removidos.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger" title="Excluir"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">Nenhuma empresa cadastrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $empresas->links() }}</div>
    </div>
</div>
@endsection
