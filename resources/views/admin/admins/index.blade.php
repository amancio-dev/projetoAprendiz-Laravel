@extends('layouts.app')

@section('title','Administradores')
@section('page-title','Administradores do Sistema')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Administradores</li>
@endsection

@section('content')
<div class="card card-outline card-dark">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-shield mr-2"></i>Administradores</h3>
        <div class="card-tools">
            <a href="{{ route('admin.admins.create') }}" class="btn btn-dark btn-sm">
                <i class="fas fa-plus mr-1"></i> Novo Administrador
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover dt-table">
            <thead class="thead-light">
                <tr>
                    <th>#</th><th>Nome</th><th>E-mail</th><th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td>
                        <strong>{{ $admin->nome }}</strong>
                        @if($admin->id === auth('web')->id())
                            <span class="badge badge-info ml-1">você</span>
                        @endif
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td class="text-center text-nowrap">
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-xs btn-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($admin->id !== auth('web')->id())
                        <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Excluir o administrador {{ addslashes($admin->nome) }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger" title="Excluir"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">Nenhum administrador cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $admins->links() }}</div>
    </div>
</div>
@endsection
