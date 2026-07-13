@extends('layouts.app')

@section('title', isset($instituicao) ? 'Editar Instituição' : 'Nova Instituição')
@section('page-title', isset($instituicao) ? 'Editar Instituição' : 'Nova Instituição')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('empresa.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('empresa.instituicoes.index') }}">Instituições</a></li>
    <li class="breadcrumb-item active">{{ isset($instituicao) ? 'Editar' : 'Nova' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-school mr-2"></i>
                    {{ isset($instituicao) ? 'Editar: '.$instituicao->nome : 'Cadastrar Instituição' }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($instituicao)
                    ? route('empresa.instituicoes.update', $instituicao)
                    : route('empresa.instituicoes.store') }}"
                    method="POST">
                    @csrf
                    @if(isset($instituicao)) @method('PUT') @endif

                    <div class="form-group">
                        <label>Nome da Instituição <span class="text-danger">*</span></label>
                        <input type="text" name="nome"
                               class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $instituicao->nome ?? '') }}"
                               required maxlength="60">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>CNPJ <span class="text-danger">*</span></label>
                                <input type="text" name="cnpj"
                                       class="form-control @error('cnpj') is-invalid @enderror"
                                       value="{{ old('cnpj', $instituicao->cnpj ?? '') }}"
                                       required maxlength="30">
                                @error('cnpj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Código da Turma</label>
                                <input type="text" name="cod_turma" class="form-control"
                                       value="{{ old('cod_turma', $instituicao->cod_turma ?? '') }}"
                                       maxlength="30" placeholder="Ex: 2026.232.2">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('empresa.instituicoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-info text-white">
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
