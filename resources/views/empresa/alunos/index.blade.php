@extends('layouts.app')

@section('title','Meus Aprendizes')
@section('page-title','Aprendizes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('empresa.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Aprendizes</li>
@endsection

@section('content')
<div class="card card-outline card-success">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate mr-2"></i>Aprendizes da empresa</h3>
        <div class="card-tools">
            <a href="{{ route('empresa.alunos.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus mr-1"></i> Novo Aprendiz
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover dt-table">
            <thead class="thead-light">
                <tr>
                    <th>Nome</th><th>E-mail</th><th>CPF</th><th>Instituição</th><th>Turma</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alunos as $aluno)
                <tr>
                    <td><strong>{{ $aluno->nome }}</strong></td>
                    <td>{{ $aluno->email }}</td>
                    <td>{{ $aluno->cpf ?? '—' }}</td>
                    <td>{{ $aluno->instituicao->nome ?? '—' }}</td>
                    <td>{{ $aluno->instituicao->cod_turma ?? '—' }}</td>
                    <td class="text-center text-nowrap">
                        <a href="{{ route('empresa.alunos.edit', $aluno) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('empresa.alunos.destroy', $aluno) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Excluir {{ addslashes($aluno->nome) }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Nenhum aprendiz cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $alunos->links() }}</div>
    </div>
</div>
@endsection
