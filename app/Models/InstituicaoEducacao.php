<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituicaoEducacao extends Model
{
    use HasFactory;

    protected $table = 'instituicoes_educacao';

    protected $fillable = [
        'nome',
        'cnpj',
        'cod_turma',
        'empresa_id',
    ];

    // Relações
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function alunos()
    {
        return $this->hasMany(Aluno::class, 'instituicao_id');
    }
}
