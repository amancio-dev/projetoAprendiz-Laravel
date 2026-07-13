<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Projeto Aprendiz') | Projeto Aprendiz</title>

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- AdminLTE --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    <style>
        .sidebar-dark-primary { background: #1a1a2e; }
        .brand-link { background: #16213e; border-bottom: 1px solid #0f3460; }
        .nav-sidebar .nav-link.active { background: #0f3460; }
        .card { box-shadow: 0 1px 6px rgba(0,0,0,.08); }
        .badge-status-0 { background: #ffc107; color:#212529; }
        .badge-status-1 { background: #28a745; color:#fff; }
        .badge-status-2 { background: #dc3545; color:#fff; }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- ── Navbar ─────────────────────────────────────────────── --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user-circle mr-1"></i>
                    @auth('web')     {{ auth('web')->user()->nome }}     @endauth
                    @auth('empresa') {{ auth('empresa')->user()->nome }} @endauth
                    @auth('aluno')   {{ auth('aluno')->user()->nome }}   @endauth
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="fas fa-sign-out-alt mr-2"></i> Sair
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- ── Sidebar ─────────────────────────────────────────────── --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-3">
        <a href="#" class="brand-link px-3">
            <i class="fas fa-graduation-cap text-primary mr-2"></i>
            <span class="brand-text font-weight-bold">Projeto Aprendiz</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle fa-2x text-secondary mt-1 ml-2"></i>
                </div>
                <div class="info">
                    @auth('web')
                        <a href="#" class="d-block text-white">{{ auth('web')->user()->nome }}</a>
                        <small class="text-muted">Administrador</small>
                    @endauth
                    @auth('empresa')
                        <a href="#" class="d-block text-white">{{ auth('empresa')->user()->nome }}</a>
                        <small class="text-muted">Empresa</small>
                    @endauth
                    @auth('aluno')
                        <a href="#" class="d-block text-white">{{ auth('aluno')->user()->nome }}</a>
                        <small class="text-muted">Aprendiz</small>
                    @endauth
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                    {{-- ── Menu Admin ──────────────────────────────── --}}
                    @auth('web')
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.empresas.index') }}" class="nav-link @if(request()->routeIs('admin.empresas.*')) active @endif">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Empresas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.alunos.index') }}" class="nav-link @if(request()->routeIs('admin.alunos.*')) active @endif">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Alunos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.instituicoes.index') }}" class="nav-link @if(request()->routeIs('admin.instituicoes.*')) active @endif">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Instituições</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.pontos.index') }}" class="nav-link @if(request()->routeIs('admin.pontos.*')) active @endif">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>Pontos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.admins.index') }}" class="nav-link @if(request()->routeIs('admin.admins.*')) active @endif">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Administradores</p>
                        </a>
                    </li>
                    @endauth

                    {{-- ── Menu Empresa ────────────────────────────── --}}
                    @auth('empresa')
                    <li class="nav-item">
                        <a href="{{ route('empresa.dashboard') }}" class="nav-link @if(request()->routeIs('empresa.dashboard')) active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('empresa.alunos.index') }}" class="nav-link @if(request()->routeIs('empresa.alunos.*')) active @endif">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Aprendizes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('empresa.instituicoes.index') }}" class="nav-link @if(request()->routeIs('empresa.instituicoes.*')) active @endif">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Instituições</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('empresa.pontos.index') }}" class="nav-link @if(request()->routeIs('empresa.pontos.*')) active @endif">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>Pontos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('empresa.perfil.edit') }}" class="nav-link @if(request()->routeIs('empresa.perfil.*')) active @endif">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Meu Perfil</p>
                        </a>
                    </li>
                    @endauth

                    {{-- ── Menu Aluno ──────────────────────────────── --}}
                    @auth('aluno')
                    <li class="nav-item">
                        <a href="{{ route('aluno.dashboard') }}" class="nav-link @if(request()->routeIs('aluno.dashboard')) active @endif">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Início</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('aluno.ponto.registrar') }}" class="nav-link @if(request()->routeIs('aluno.ponto.registrar')) active @endif">
                            <i class="nav-icon fas fa-map-marker-alt"></i>
                            <p>Registrar Ponto</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('aluno.ponto.historico') }}" class="nav-link @if(request()->routeIs('aluno.ponto.historico')) active @endif">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Histórico</p>
                        </a>
                    </li>
                    @endauth

                </ul>
            </nav>
        </div>
    </aside>

    {{-- ── Conteúdo ─────────────────────────────────────────────── --}}
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                {{-- Alertas globais --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-sm">
        <strong>Projeto Aprendiz</strong> &mdash; Sistema de gerenciamento de aprendizes.
    </footer>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    // DataTable padrão para todas as tabelas com a classe .dt-table
    $(document).ready(function () {
        $('.dt-table').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
            },
            responsive: true,
        });
    });

    // Setup CSRF para requisições Ajax/PATCH
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
</script>

@stack('scripts')
</body>
</html>
