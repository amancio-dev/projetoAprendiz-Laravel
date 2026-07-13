@extends('layouts.app')

@section('title','Instituições de Ensino')
@section('page-title','Instituições de Ensino')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Instituições</li>
@endsection

@section('content')
<div class="card card-outline card-secondary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-school mr-2"></i>Instituições cadastradas</h3>
        <div class="card-tools">
            <a href="{{ route('admin.instituicoes.create') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-plus mr-1"></i> Nova Instituição
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover dt-table">
            <thead class="thead-light">
                <tr>
                    <th>#</th><th>Nome</th><th>CNPJ</th><th>Turma</th><th>Empresa</th>
                    <th class="text-center">Alunos</th><th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instituicoes as $inst)
                <tr>
                    <td>{{ $inst->id }}</td>
                    <td><strong>{{ $inst->nome }}</strong></td>
                    <td>{{ $inst->cnpj }}</td>
                    <td>{{ $inst->cod_turma ?? '—' }}</td>
                    <td>{{ $inst->empresa->nome ?? '—' }}</td>
                    <td class="text-center"><span class="badge badge-success">{{ $inst->alunos_count }}</span></td>
                    <td class="text-center text-nowrap">
                        <a href="{{ route('admin.instituicoes.edit', $inst) }}" class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.instituicoes.destroy', $inst) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Excluir a instituição {{ addslashes($inst->nome) }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">Nenhuma instituição cadastrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $instituicoes->links() }}</div>
    </div>
</div>
@endsection
