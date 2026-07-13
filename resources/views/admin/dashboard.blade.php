@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- Stats Cards --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['empresas'] }}</h3>
                <p>Empresas</p>
            </div>
            <div class="icon"><i class="fas fa-building"></i></div>
            <a href="{{ route('admin.empresas.index') }}" class="small-box-footer">
                Ver todas <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['alunos'] }}</h3>
                <p>Aprendizes</p>
            </div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
            <a href="{{ route('admin.alunos.index') }}" class="small-box-footer">
                Ver todos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $stats['instituicoes'] }}</h3>
                <p>Instituições</p>
            </div>
            <div class="icon"><i class="fas fa-school"></i></div>
            <a href="{{ route('admin.instituicoes.index') }}" class="small-box-footer">
                Ver todas <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pontos_pendentes'] }}</h3>
                <p>Pontos Pendentes</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="{{ route('admin.pontos.index', ['status' => 0]) }}" class="small-box-footer">
                Ver pontos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Pontos recentes --}}
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clock mr-2"></i>Pontos Recentes</h3>
        <div class="card-tools">
            <a href="{{ route('admin.pontos.index') }}" class="btn btn-sm btn-primary">
                Ver todos
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Aprendiz</th>
                    <th>Empresa</th>
                    <th>Instituição</th>
                    <th>Data/Hora</th>
                    <th>Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pontos_recentes as $ponto)
                <tr>
                    <td>{{ $ponto->nome_aluno }}</td>
                    <td>{{ $ponto->nome_empresa }}</td>
                    <td>{{ $ponto->nome_instituicao }}</td>
                    <td>{{ $ponto->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge badge-status-{{ $ponto->status }}">
                            {{ $ponto->status_label }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($ponto->status == 0)
                        <form action="{{ route('admin.pontos.aprovar', $ponto) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-success" title="Aprovar">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.pontos.rejeitar', $ponto) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-danger" title="Rejeitar">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">Nenhum ponto registrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
