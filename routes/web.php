<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Empresa;
use App\Http\Controllers\Aluno;

/*
|--------------------------------------------------------------------------
| Rota raiz → redireciona para login
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Autenticação (pública)
|--------------------------------------------------------------------------
*/
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

Route::get('/esqueci-senha',            [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/esqueci-senha',           [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/redefinir-senha/{token}',  [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/redefinir-senha',         [PasswordResetController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Administrador (guard: web)
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('admins',      Admin\AdminController::class)->except(['show']);
    Route::resource('empresas',    Admin\EmpresaController::class);
    Route::resource('alunos',      Admin\AlunoController::class);
    Route::resource('instituicoes',Admin\InstituicaoController::class)->parameters(['instituicoes' => 'instituicao']);

    Route::get('/pontos',                     [Admin\PontoController::class, 'index'])->name('pontos.index');
    Route::patch('/pontos/{ponto}/aprovar',   [Admin\PontoController::class, 'aprovar'])->name('pontos.aprovar');
    Route::patch('/pontos/{ponto}/rejeitar',  [Admin\PontoController::class, 'rejeitar'])->name('pontos.rejeitar');

    // Ajax: instituições por empresa (para select dinâmico)
    Route::get('/empresas/{empresa}/instituicoes', [Admin\AlunoController::class, 'instituicoesPorEmpresa'])
        ->name('empresas.instituicoes');
});

/*
|--------------------------------------------------------------------------
| Empresa (guard: empresa)
|--------------------------------------------------------------------------
*/
Route::middleware(['role:empresa'])
    ->prefix('empresa')
    ->name('empresa.')
    ->group(function () {

    Route::get('/dashboard', [Empresa\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/perfil',  [Empresa\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil',  [Empresa\PerfilController::class, 'update'])->name('perfil.update');

    Route::resource('alunos',       Empresa\AlunoController::class);
    Route::resource('instituicoes', Empresa\InstituicaoController::class)->parameters(['instituicoes' => 'instituicao']);

    Route::get('/pontos',                       [Empresa\PontoController::class, 'index'])->name('pontos.index');
    Route::patch('/pontos/{ponto}/aprovar',     [Empresa\PontoController::class, 'aprovar'])->name('pontos.aprovar');
    Route::patch('/pontos/{ponto}/rejeitar',    [Empresa\PontoController::class, 'rejeitar'])->name('pontos.rejeitar');
});

/*
|--------------------------------------------------------------------------
| Aluno (guard: aluno)
|--------------------------------------------------------------------------
*/
Route::middleware(['role:aluno'])
    ->prefix('aluno')
    ->name('aluno.')
    ->group(function () {

    Route::get('/dashboard',          [Aluno\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ponto/registrar',    [Aluno\PontoController::class, 'registrar'])->name('ponto.registrar');
    Route::post('/ponto',             [Aluno\PontoController::class, 'store'])->name('ponto.store');
    Route::get('/ponto/historico',    [Aluno\PontoController::class, 'historico'])->name('ponto.historico');
});
