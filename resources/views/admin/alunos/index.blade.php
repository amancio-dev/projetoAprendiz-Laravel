@extends('layouts.app')

@section('title','Gerenciar Alunos')
@section('page-title','Aprendizes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Aprendizes</li>
@endsection

@section('content')
<div class="card card-outline card-success">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate mr-2"></i>Aprendizes cadastrados</h3>
        <div class="card-tools">
            <a href="{{ route('admin.alunos.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus mr-1"></i> Novo Aprendiz
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover dt-table">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>CPF</th>
                    <th>Empresa</th>
                    <th>Instituição</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alunos as $aluno)
                <tr>
                    <td>{{ $aluno->id }}</td>
                    <td><strong>{{ $aluno->nome }}</strong></td>
                    <td>{{ $aluno->email }}</td>
                    <td>{{ $aluno->cpf ?? '—' }}</td>
                    <td>{{ $aluno->empresa->nome ?? '—' }}</td>
                    <td>
                        {{ $aluno->instituicao->nome ?? '—' }}
                        @if($aluno->instituicao)
                            <br><small class="text-muted">Turma: {{ $aluno->instituicao->cod_turma }}</small>
                        @endif
                    </td>
                    <td class="text-center text-nowrap">
                        <a href="{{ route('admin.alunos.edit', $aluno) }}" class="btn btn-xs btn-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.alunos.destroy', $aluno) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Excluir o aprendiz {{ addslashes($aluno->nome) }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger" title="Excluir"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">Nenhum aprendiz cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $alunos->links() }}</div>
    </div>
</div>
@endsection
