<?php

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\InstituicaoEducacao;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AlunoFactory extends Factory
{
    protected $model = \App\Models\Aluno::class;

    public function definition(): array
    {
        return [
            'nome'           => $this->faker->name(),
            'cpf'            => $this->faker->numerify('###.###.###-##'),
            'email'          => $this->faker->unique()->safeEmail(),
            'password'       => Hash::make('senha123'),
            'empresa_id'     => Empresa::factory(),
            'instituicao_id' => InstituicaoEducacao::factory(),
        ];
    }
}
