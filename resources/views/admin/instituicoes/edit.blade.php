@extends('layouts.app')

@section('title', isset($instituicao) ? 'Editar Instituição' : 'Nova Instituição')
@section('page-title', isset($instituicao) ? 'Editar Instituição' : 'Nova Instituição')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.instituicoes.index') }}">Instituições</a></li>
    <li class="breadcrumb-item active">{{ isset($instituicao) ? 'Editar' : 'Nova' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-school mr-2"></i>
                    {{ isset($instituicao) ? 'Editar: '.$instituicao->nome : 'Nova Instituição' }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($instituicao) ? route('admin.instituicoes.update', $instituicao) : route('admin.instituicoes.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($instituicao)) @method('PUT') @endif

                    <div class="form-group">
                        <label>Nome <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $instituicao->nome ?? '') }}" required maxlength="60">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CNPJ <span class="text-danger">*</span></label>
                                <input type="text" name="cnpj" class="form-control @error('cnpj') is-invalid @enderror"
                                       value="{{ old('cnpj', $instituicao->cnpj ?? '') }}" required maxlength="30">
                                @error('cnpj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Código da Turma</label>
                                <input type="text" name="cod_turma" class="form-control"
                                       value="{{ old('cod_turma', $instituicao->cod_turma ?? '') }}" maxlength="30"
                                       placeholder="Ex: 2026.232.2">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Empresa Responsável <span class="text-danger">*</span></label>
                        <select name="empresa_id" class="form-control @error('empresa_id') is-invalid @enderror" required>
                            <option value="">Selecione a empresa</option>
                            @foreach($empresas as $emp)
                                <option value="{{ $emp->id }}"
                                    @if(old('empresa_id', $instituicao->empresa_id ?? '') == $emp->id) selected @endif>
                                    {{ $emp->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('empresa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('admin.instituicoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-save mr-1"></i>
                            {{ isset($instituicao) ? 'Atualizar' : 'Cadastrar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
