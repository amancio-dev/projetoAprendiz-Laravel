@extends('layouts.app')

@section('title', isset($empresa) ? 'Editar Empresa' : 'Nova Empresa')
@section('page-title', isset($empresa) ? 'Editar Empresa' : 'Nova Empresa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.empresas.index') }}">Empresas</a></li>
    <li class="breadcrumb-item active">{{ isset($empresa) ? 'Editar' : 'Nova' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building mr-2"></i>
                    {{ isset($empresa) ? 'Editar: '.$empresa->nome : 'Cadastrar nova empresa' }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($empresa) ? route('admin.empresas.update', $empresa) : route('admin.empresas.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($empresa)) @method('PUT') @endif

                    <div class="form-group">
                        <label>Nome da Empresa <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $empresa->nome ?? '') }}" required maxlength="60">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CNPJ <span class="text-danger">*</span></label>
                                <input type="text" name="cnpj" class="form-control @error('cnpj') is-invalid @enderror"
                                       value="{{ old('cnpj', $empresa->cnpj ?? '') }}" required maxlength="30">
                                @error('cnpj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>E-mail <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $empresa->email ?? '') }}" required maxlength="100">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Senha {{ isset($empresa) ? '(deixe em branco para não alterar)' : '' }} <span class="text-danger">*</span></label>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       {{ !isset($empresa) ? 'required' : '' }} minlength="6"
                                       placeholder="{{ isset($empresa) ? 'Nova senha (opcional)' : 'Mínimo 6 caracteres' }}">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirmar Senha</label>
                                <input type="password" name="password_confirmation"
                                       class="form-control"
                                       placeholder="Confirme a senha">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('admin.empresas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            {{ isset($empresa) ? 'Atualizar' : 'Cadastrar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
