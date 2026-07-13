@extends('layouts.app')

@section('title', isset($aluno) ? 'Editar Aprendiz' : 'Novo Aprendiz')
@section('page-title', isset($aluno) ? 'Editar Aprendiz' : 'Novo Aprendiz')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.alunos.index') }}">Aprendizes</a></li>
    <li class="breadcrumb-item active">{{ isset($aluno) ? 'Editar' : 'Novo' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate mr-2"></i>
                    {{ isset($aluno) ? 'Editar: '.$aluno->nome : 'Cadastrar novo aprendiz' }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($aluno) ? route('admin.alunos.update', $aluno) : route('admin.alunos.store') }}"
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
                               value="{{ old('email', $aluno->email ?? '') }}" required maxlength="100">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Senha {{ isset($aluno) ? '(opcional)' : '' }} <span class="text-danger">{{ !isset($aluno) ? '*' : '' }}</span></label>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Empresa <span class="text-danger">*</span></label>
                                <select name="empresa_id" id="empresa_id"
                                        class="form-control @error('empresa_id') is-invalid @enderror" required>
                                    <option value="">Selecione a empresa</option>
                                    @foreach($empresas as $emp)
                                        <option value="{{ $emp->id }}"
                                            @if(old('empresa_id', $aluno->empresa_id ?? '') == $emp->id) selected @endif>
                                            {{ $emp->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('empresa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Instituição de Ensino <span class="text-danger">*</span></label>
                                <select name="instituicao_id" id="instituicao_id"
                                        class="form-control @error('instituicao_id') is-invalid @enderror" required>
                                    <option value="">Selecione a empresa primeiro</option>
                                    @if(isset($instituicoes))
                                        @foreach($instituicoes as $inst)
                                            <option value="{{ $inst->id }}"
                                                @if(old('instituicao_id', $aluno->instituicao_id ?? '') == $inst->id) selected @endif>
                                                {{ $inst->nome }} ({{ $inst->cod_turma }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('instituicao_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('admin.alunos.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
// Carrega instituições dinamicamente ao selecionar empresa
$('#empresa_id').on('change', function () {
    const empresaId = $(this).val();
    const $inst = $('#instituicao_id');
    $inst.html('<option value="">Carregando...</option>').prop('disabled', true);

    if (!empresaId) {
        $inst.html('<option value="">Selecione a empresa primeiro</option>').prop('disabled', false);
        return;
    }

    $.getJSON('{{ route("admin.empresas.instituicoes", ":id") }}'.replace(':id', empresaId))
        .done(function (data) {
            let opts = '<option value="">Selecione a instituição</option>';
            data.forEach(i => {
                opts += `<option value="${i.id}">${i.nome} (${i.cod_turma || 'sem turma'})</option>`;
            });
            $inst.html(opts).prop('disabled', false);
        })
        .fail(function () {
            $inst.html('<option value="">Erro ao carregar</option>').prop('disabled', false);
        });
});
</script>
@endpush
