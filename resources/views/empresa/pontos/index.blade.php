@extends('layouts.app')

@section('title','Pontos dos Aprendizes')
@section('page-title','Pontos de Presença')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('empresa.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pontos</li>
@endsection

@section('content')

{{-- Filtros --}}
<div class="card card-outline card-warning mb-3">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filtros</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="form-inline flex-wrap">
            <div class="form-group mr-3 mb-2">
                <label class="mr-2">Status:</label>
                <select name="status" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <option value="0" @selected(request('status') === '0')>⏳ Pendente</option>
                    <option value="1" @selected(request('status') === '1')>✅ Aprovado</option>
                    <option value="2" @selected(request('status') === '2')>❌ Rejeitado</option>
                </select>
            </div>
            <div class="form-group mr-3 mb-2">
                <label class="mr-2">Aprendiz:</label>
                <select name="aluno_id" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    @foreach($alunos as $a)
                        <option value="{{ $a->id }}" @selected(request('aluno_id') == $a->id)>{{ $a->nome }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-warning btn-sm mb-2">
                <i class="fas fa-search mr-1"></i>Filtrar
            </button>
            <a href="{{ route('empresa.pontos.index') }}" class="btn btn-secondary btn-sm mb-2 ml-2">
                <i class="fas fa-times mr-1"></i>Limpar
            </a>
        </form>
    </div>
</div>

{{-- Tabela --}}
<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-clock mr-2"></i>
            Registros
            @if(request('status') === '0')
                <span class="badge badge-warning ml-2">Pendentes</span>
            @elseif(request('status') === '1')
                <span class="badge badge-success ml-2">Aprovados</span>
            @elseif(request('status') === '2')
                <span class="badge badge-danger ml-2">Rejeitados</span>
            @endif
        </h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Aprendiz</th>
                    <th>Instituição</th>
                    <th>Localização</th>
                    <th>Data/Hora</th>
                    <th>Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pontos as $ponto)
                <tr>
                    <td><strong>{{ $ponto->nome_aluno }}</strong></td>
                    <td>{{ $ponto->nome_instituicao }}</td>
                    <td>
                        @if($ponto->localizacao)
                            <a href="https://maps.google.com/?q={{ $ponto->localizacao }}"
                               target="_blank" class="text-primary text-sm">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $ponto->localizacao }}
                            </a>
                        @else
                            <span class="text-muted">Não informada</span>
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
                            <form action="{{ route('empresa.pontos.aprovar', $ponto) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-xs btn-success" title="Aprovar">
                                    <i class="fas fa-check"></i> Aprovar
                                </button>
                            </form>
                            <form action="{{ route('empresa.pontos.rejeitar', $ponto) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-xs btn-danger" title="Rejeitar">
                                    <i class="fas fa-times"></i> Rejeitar
                                </button>
                            </form>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Nenhum ponto encontrado.
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
