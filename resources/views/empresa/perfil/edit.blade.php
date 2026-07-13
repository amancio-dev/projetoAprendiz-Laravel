@extends('layouts.app')

@section('title','Meu Perfil')
@section('page-title','Dados da Empresa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('empresa.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Meu Perfil</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-building mr-2"></i>Dados da minha empresa</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('empresa.perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nome da Empresa <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $empresa->nome) }}" required maxlength="60">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CNPJ <span class="text-danger">*</span></label>
                                <input type="text" name="cnpj" class="form-control @error('cnpj') is-invalid @enderror"
                                       value="{{ old('cnpj', $empresa->cnpj) }}" required maxlength="30">
                                @error('cnpj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>E-mail <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $empresa->email) }}" required maxlength="100">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted small">Deixe os campos abaixo em branco para manter a senha atual.</p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nova Senha</label>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror" minlength="6">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirmar Nova Senha</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
