<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class EmpresaFactory extends Factory
{
    protected $model = \App\Models\Empresa::class;

    public function definition(): array
    {
        return [
            'nome'     => $this->faker->company(),
            'cnpj'     => $this->faker->unique()->numerify('##.###.###/####-##'),
            'email'    => $this->faker->unique()->companyEmail(),
            'password' => Hash::make('senha123'),
            'admin_id' => Admin::factory(),
        ];
    }
}
