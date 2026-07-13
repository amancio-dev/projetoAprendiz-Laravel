@extends('layouts.app')

@section('title','Minha Área')
@section('page-title','Minha Área')

@section('breadcrumb')
    <li class="breadcrumb-item active">Início</li>
@endsection

@section('content')

{{-- Boas vindas --}}
<div class="callout callout-info mb-4">
    <h5><i class="fas fa-graduation-cap mr-2"></i>Olá, {{ $aluno->nome }}!</h5>
    <p class="mb-0">
        Empresa: <strong>{{ $aluno->empresa->nome ?? '—' }}</strong> &nbsp;|&nbsp;
        Instituição: <strong>{{ $aluno->instituicao->nome ?? '—' }}</strong>
        @if($aluno->instituicao)
            &nbsp;|&nbsp; Turma: <strong>{{ $aluno->instituicao->cod_turma }}</strong>
        @endif
    </p>
</div>

{{-- Stats --}}
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total de Pontos</p>
            </div>
            <div class="icon"><i class="fas fa-list"></i></div>
            <a href="{{ route('aluno.ponto.historico') }}" class="small-box-footer">
                Ver histórico <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['aprovados'] }}</h3>
                <p>Aprovados</p>
            </div>
            <div class="icon"><i class="fas fa-check-double"></i></div>
            <a href="{{ route('aluno.ponto.historico', ['status' => 1]) }}" class="small-box-footer">
                Ver aprovados <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pendentes'] }}</h3>
                <p>Aguardando aprovação</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="{{ route('aluno.ponto.historico', ['status' => 0]) }}" class="small-box-footer">
                Ver pendentes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Ação principal --}}
<div class="row">
    <div class="col-md-5">
        <div class="card card-primary card-outline">
            <div class="card-body text-center py-4">
                <i class="fas fa-map-marker-alt fa-4x text-primary mb-3"></i>
                <h4>Registrar Ponto</h4>
                <p class="text-muted">Registre sua presença com localização geográfica.</p>
                <a href="{{ route('aluno.ponto.registrar') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-clock mr-2"></i>Registrar agora
                </a>
            </div>
        </div>
    </div>

    {{-- Últimos pontos --}}
    <div class="col-md-7">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i>Últimos registros
                </h3>
                <div class="card-tools">
                    <a href="{{ route('aluno.ponto.historico') }}" class="btn btn-sm btn-secondary">Ver todos</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>Data/Hora</th><th>Localização</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($pontos_recentes as $ponto)
                        <tr>
                            <td>{{ $ponto->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($ponto->localizacao)
                                    <a href="https://maps.google.com/?q={{ $ponto->localizacao }}"
                                       target="_blank" class="text-primary text-sm">
                                        <i class="fas fa-map-marker-alt"></i> Ver
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-status-{{ $ponto->status }}">
                                    {{ $ponto->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">
                                Nenhum ponto registrado ainda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
