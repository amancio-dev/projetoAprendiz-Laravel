<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponto extends Model
{
    use HasFactory;

    protected $table = 'pontos';

    protected $fillable = [
        'aluno_id',
        'empresa_id',
        'nome_aluno',
        'nome_empresa',
        'nome_instituicao',
        'localizacao',
        'status',
    ];

    const STATUS_PENDENTE  = 0;
    const STATUS_APROVADO  = 1;
    const STATUS_REJEITADO = 2;

    // Relações
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // Acessores
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDENTE  => 'Pendente',
            self::STATUS_APROVADO  => 'Aprovado',
            self::STATUS_REJEITADO => 'Rejeitado',
            default                => 'Desconhecido',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDENTE  => 'warning',
            self::STATUS_APROVADO  => 'success',
            self::STATUS_REJEITADO => 'danger',
            default                => 'secondary',
        };
    }

    // Scopes
    public function scopePendente($query)
    {
        return $query->where('status', self::STATUS_PENDENTE);
    }

    public function scopeAprovado($query)
    {
        return $query->where('status', self::STATUS_APROVADO);
    }
}
