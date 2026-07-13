<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstituicaoEducacaoFactory extends Factory
{
    protected $model = \App\Models\InstituicaoEducacao::class;

    public function definition(): array
    {
        return [
            'nome'       => $this->faker->company().' - Instituto',
            'cnpj'       => $this->faker->unique()->numerify('##.###.###/####-##'),
            'cod_turma'  => $this->faker->bothify('####.###.#'),
            'empresa_id' => Empresa::factory(),
        ];
    }
}
