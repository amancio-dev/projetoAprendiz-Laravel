@extends('layouts.app')

@section('title','Dashboard Empresa')
@section('page-title','Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $stats['alunos'] }}</h3><p>Aprendizes</p></div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
            <a href="{{ route('empresa.alunos.index') }}" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $stats['instituicoes'] }}</h3><p>Instituições</p></div>
            <div class="icon"><i class="fas fa-school"></i></div>
            <a href="{{ route('empresa.instituicoes.index') }}" class="small-box-footer">Ver todas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ $stats['pontos_pendentes'] }}</h3><p>Pontos Pendentes</p></div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="{{ route('empresa.pontos.index', ['status'=>0]) }}" class="small-box-footer">Aprovar pontos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner"><h3>{{ $stats['pontos_aprovados'] }}</h3><p>Pontos Aprovados</p></div>
            <div class="icon"><i class="fas fa-check-double"></i></div>
            <a href="{{ route('empresa.pontos.index', ['status'=>1]) }}" class="small-box-footer">Ver aprovados <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clock mr-2"></i>Pontos Aguardando Aprovação</h3>
        <div class="card-tools">
            <a href="{{ route('empresa.pontos.index') }}" class="btn btn-sm btn-primary">Ver todos</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Aprendiz</th><th>Instituição</th><th>Localização</th>
                    <th>Data/Hora</th><th>Status</th><th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pontos_recentes as $ponto)
                <tr>
                    <td>{{ $ponto->nome_aluno }}</td>
                    <td>{{ $ponto->nome_instituicao }}</td>
                    <td>
                        @if($ponto->localizacao)
                            <a href="https://maps.google.com/?q={{ $ponto->localizacao }}" target="_blank" class="text-sm text-primary">
                                <i class="fas fa-map-marker-alt"></i> Ver mapa
                            </a>
                        @else <span class="text-muted">—</span> @endif
                    </td>
                    <td>{{ $ponto->created_at->format('d/m/Y H:i') }}</td>
                    <td><span class="badge badge-status-{{ $ponto->status }}">{{ $ponto->status_label }}</span></td>
                    <td class="text-center text-nowrap">
                        @if($ponto->status == 0)
                        <form action="{{ route('empresa.pontos.aprovar', $ponto) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-success" title="Aprovar"><i class="fas fa-check"></i></button>
                        </form>
                        <form action="{{ route('empresa.pontos.rejeitar', $ponto) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-danger" title="Rejeitar"><i class="fas fa-times"></i></button>
                        </form>
                        @else <span class="text-muted">—</span> @endif
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
