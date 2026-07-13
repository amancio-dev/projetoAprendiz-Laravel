@extends('layouts.app')

@section('title', isset($admin) ? 'Editar Administrador' : 'Novo Administrador')
@section('page-title', isset($admin) ? 'Editar Administrador' : 'Novo Administrador')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Administradores</a></li>
    <li class="breadcrumb-item active">{{ isset($admin) ? 'Editar' : 'Novo' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card card-outline card-dark">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-shield mr-2"></i>
                    {{ isset($admin) ? 'Editar: '.$admin->nome : 'Novo Administrador' }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($admin) ? route('admin.admins.update', $admin) : route('admin.admins.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($admin)) @method('PUT') @endif

                    <div class="form-group">
                        <label>Nome <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $admin->nome ?? '') }}" required maxlength="60">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>E-mail <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $admin->email ?? '') }}" required maxlength="100">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Senha {{ isset($admin) ? '(opcional)' : '' }} <span class="text-danger">{{ !isset($admin) ? '*' : '' }}</span></label>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       {{ !isset($admin) ? 'required' : '' }} minlength="6">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirmar Senha</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-save mr-1"></i>
                            {{ isset($admin) ? 'Atualizar' : 'Cadastrar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
