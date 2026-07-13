<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Empresa extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'empresas';

    protected $fillable = [
        'nome',
        'cnpj',
        'email',
        'password',
        'admin_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Relações
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function alunos()
    {
        return $this->hasMany(Aluno::class, 'empresa_id');
    }

    public function instituicoes()
    {
        return $this->hasMany(InstituicaoEducacao::class, 'empresa_id');
    }

    public function pontos()
    {
        return $this->hasMany(Ponto::class, 'empresa_id');
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token, 'empresa'));
    }
}
