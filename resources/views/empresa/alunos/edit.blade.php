@extends('layouts.app')

@section('title', isset($aluno) ? 'Editar Aprendiz' : 'Novo Aprendiz')
@section('page-title', isset($aluno) ? 'Editar Aprendiz' : 'Novo Aprendiz')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('empresa.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('empresa.alunos.index') }}">Aprendizes</a></li>
    <li class="breadcrumb-item active">{{ isset($aluno) ? 'Editar' : 'Novo' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate mr-2"></i>
                    {{ isset($aluno) ? 'Editar: '.$aluno->nome : 'Cadastrar Aprendiz' }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($aluno) ? route('empresa.alunos.update',$aluno) : route('empresa.alunos.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($aluno)) @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nome <span class="text-danger">*</span></label>
                                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                       value="{{ old('nome', $aluno->nome ?? '') }}" required maxlength="60">
                                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CPF</label>
                                <input type="text" name="cpf" class="form-control"
                                       value="{{ old('cpf', $aluno->cpf ?? '') }}" maxlength="30">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>E-mail <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $aluno->email ?? '') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Senha {{ isset($aluno) ? '(opcional)' : '' }} <span class="text-danger">{{ !isset($aluno) ? '*' : '' }}</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       {{ !isset($aluno) ? 'required' : '' }} minlength="6">
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

                    <div class="form-group">
                        <label>Instituição de Ensino <span class="text-danger">*</span></label>
                        <select name="instituicao_id" class="form-control @error('instituicao_id') is-invalid @enderror" required>
                            <option value="">Selecione</option>
                            @foreach($instituicoes as $inst)
                                <option value="{{ $inst->id }}"
                                    @if(old('instituicao_id', $aluno->instituicao_id ?? '') == $inst->id) selected @endif>
                                    {{ $inst->nome }} — Turma: {{ $inst->cod_turma }}
                                </option>
                            @endforeach
                        </select>
                        @error('instituicao_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if($instituicoes->isEmpty())
                            <small class="text-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Nenhuma instituição cadastrada. <a href="{{ route('empresa.instituicoes.create') }}">Cadastrar agora</a>.
                            </small>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('empresa.alunos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i>
                            {{ isset($aluno) ? 'Atualizar' : 'Cadastrar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
