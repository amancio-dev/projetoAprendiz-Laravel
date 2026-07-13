@extends('layouts.app')

@section('title','Gerenciar Pontos')
@section('page-title','Pontos de Presença')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pontos</li>
@endsection

@section('content')
{{-- Filtros --}}
<div class="card card-outline card-warning mb-3">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filtros</h3></div>
    <div class="card-body">
        <form method="GET" class="form-inline flex-wrap gap-2">
            <select name="status" class="form-control mr-2 mb-2">
                <option value="">Todos os status</option>
                <option value="0" @if(request('status')==='0') selected @endif>⏳ Pendente</option>
                <option value="1" @if(request('status')==='1') selected @endif>✅ Aprovado</option>
                <option value="2" @if(request('status')==='2') selected @endif>❌ Rejeitado</option>
            </select>
            <button type="submit" class="btn btn-warning mb-2"><i class="fas fa-search mr-1"></i>Filtrar</button>
            <a href="{{ route('admin.pontos.index') }}" class="btn btn-secondary mb-2 ml-2">Limpar</a>
        </form>
    </div>
</div>

<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clock mr-2"></i>Registros de ponto</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Aprendiz</th>
                    <th>Empresa</th>
                    <th>Instituição</th>
                    <th>Localização</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pontos as $ponto)
                <tr>
                    <td>{{ $ponto->id }}</td>
                    <td>{{ $ponto->nome_aluno }}</td>
                    <td>{{ $ponto->nome_empresa }}</td>
                    <td>{{ $ponto->nome_instituicao }}</td>
                    <td>
                        @if($ponto->localizacao)
                            <a href="https://maps.google.com/?q={{ $ponto->localizacao }}" target="_blank" class="text-primary">
                                <i class="fas fa-map-marker-alt"></i> Ver mapa
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $ponto->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge badge-status-{{ $ponto->status }}">
                            {{ $ponto->status_label }}
                        </span>
                    </td>
                    <td class="text-center text-nowrap">
                        @if($ponto->status == 0)
                        <form action="{{ route('admin.pontos.aprovar', $ponto) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-success" title="Aprovar"><i class="fas fa-check"></i></button>
                        </form>
                        <form action="{{ route('admin.pontos.rejeitar', $ponto) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-danger" title="Rejeitar"><i class="fas fa-times"></i></button>
                        </form>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Nenhum ponto encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $pontos->withQueryString()->links() }}</div>
</div>
@endsection
