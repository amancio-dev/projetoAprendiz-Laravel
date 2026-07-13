@extends('layouts.app')

@section('title','Histórico de Pontos')
@section('page-title','Histórico de Pontos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aluno.dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Histórico</li>
@endsection

@section('content')

{{-- Filtro por status --}}
<div class="btn-group mb-3" role="group">
    <a href="{{ route('aluno.ponto.historico') }}"
       class="btn btn-sm {{ !request('status') ? 'btn-secondary' : 'btn-outline-secondary' }}">
        Todos
    </a>
    <a href="{{ route('aluno.ponto.historico', ['status' => 0]) }}"
       class="btn btn-sm {{ request('status') === '0' ? 'btn-warning' : 'btn-outline-warning' }}">
        ⏳ Pendentes
    </a>
    <a href="{{ route('aluno.ponto.historico', ['status' => 1]) }}"
       class="btn btn-sm {{ request('status') === '1' ? 'btn-success' : 'btn-outline-success' }}">
        ✅ Aprovados
    </a>
    <a href="{{ route('aluno.ponto.historico', ['status' => 2]) }}"
       class="btn btn-sm {{ request('status') === '2' ? 'btn-danger' : 'btn-outline-danger' }}">
        ❌ Rejeitados
    </a>
</div>

<div class="card card-outline card-secondary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-history mr-2"></i>Meus registros de ponto
        </h3>
        <div class="card-tools">
            <a href="{{ route('aluno.ponto.registrar') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i>Novo Registro
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Empresa</th>
                    <th>Instituição</th>
                    <th>Localização</th>
                    <th>Data/Hora</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pontos as $ponto)
                <tr>
                    <td>{{ $ponto->id }}</td>
                    <td>{{ $ponto->nome_empresa }}</td>
                    <td>{{ $ponto->nome_instituicao }}</td>
                    <td>
                        @if($ponto->localizacao)
                            <a href="https://maps.google.com/?q={{ $ponto->localizacao }}"
                               target="_blank" class="text-primary">
                                <i class="fas fa-map-marker-alt"></i> Ver no mapa
                            </a>
                        @else
                            <span class="text-muted">Não registrada</span>
                        @endif
                    </td>
                    <td>{{ $ponto->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge badge-status-{{ $ponto->status }}">
                            {{ $ponto->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        Nenhum ponto registrado
                        @if(request('status') !== null) com este status @endif.
                        <br>
                        <a href="{{ route('aluno.ponto.registrar') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="fas fa-plus mr-1"></i> Registrar agora
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $pontos->withQueryString()->links() }}
    </div>
</div>
@endsection
