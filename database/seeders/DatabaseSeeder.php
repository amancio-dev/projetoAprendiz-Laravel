<?php

namespace Database\Seeders;

use App\Models\{Admin, Aluno, Empresa, InstituicaoEducacao, Ponto};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Senha padrão para todos os usuários de teste: senha123
     */
    public function run(): void
    {
        // ── Administrador ─────────────────────────────────────────────
        $admin = Admin::create([
            'nome'     => 'Administrador',
            'email'    => 'admin@projetoaprendiz.com',
            'password' => Hash::make('senha123'),
        ]);

        // ── Empresas ──────────────────────────────────────────────────
        $senac = Empresa::create([
            'nome'     => 'Senac',
            'cnpj'     => '14.545.413/0001-00',
            'email'    => 'senac@senac.com',
            'password' => Hash::make('senha123'),
            'admin_id' => $admin->id,
        ]);

        $sescs = Empresa::create([
            'nome'     => 'Sescs',
            'cnpj'     => '09.876.600/0001-00',
            'email'    => 'sescs@sescs.com',
            'password' => Hash::make('senha123'),
            'admin_id' => $admin->id,
        ]);

        // ── Instituições de Educação ───────────────────────────────────
        $ifal = InstituicaoEducacao::create([
            'nome'       => 'IFAL',
            'cnpj'       => '12.345.678/0001-00',
            'cod_turma'  => '2026.232.2',
            'empresa_id' => $sescs->id,
        ]);

        $ufal = InstituicaoEducacao::create([
            'nome'       => 'UFAL',
            'cnpj'       => '33.330.000/0001-33',
            'cod_turma'  => '3333',
            'empresa_id' => $sescs->id,
        ]);

        InstituicaoEducacao::create([
            'nome'       => 'Escola Maria das Graças',
            'cnpj'       => '22.220.000/0001-22',
            'cod_turma'  => '22.22.22',
            'empresa_id' => $senac->id,
        ]);

        $escola = InstituicaoEducacao::create([
            'nome'       => 'Escola do Ricardo',
            'cnpj'       => '85.858.500/0001-85',
            'cod_turma'  => '2026.232.223',
            'empresa_id' => $sescs->id,
        ]);

        // ── Alunos ────────────────────────────────────────────────────
        $aluno1 = Aluno::create([
            'nome'           => 'Aluno 1',
            'cpf'            => '123.456.789-00',
            'email'          => 'aluno1@aluno.com',
            'password'       => Hash::make('senha123'),
            'empresa_id'     => $sescs->id,
            'instituicao_id' => $ufal->id,
        ]);

        $aluno2 = Aluno::create([
            'nome'           => 'Aluno 2',
            'cpf'            => '456.789.123-00',
            'email'          => 'aluno2@aluno.com',
            'password'       => Hash::make('senha123'),
            'empresa_id'     => $sescs->id,
            'instituicao_id' => $ufal->id,
        ]);

        Aluno::create([
            'nome'           => 'Aluno 3',
            'cpf'            => '789.123.456-00',
            'email'          => 'aluno3@aluno.com',
            'password'       => Hash::make('senha123'),
            'empresa_id'     => $senac->id,
            'instituicao_id' => $ifal->id,
        ]);

        // ── Pontos de exemplo ─────────────────────────────────────────
        Ponto::create([
            'aluno_id'         => $aluno1->id,
            'empresa_id'       => $sescs->id,
            'nome_aluno'       => $aluno1->nome,
            'nome_empresa'     => $sescs->nome,
            'nome_instituicao' => $ufal->nome,
            'localizacao'      => '-9.6643, -35.7279',
            'status'           => Ponto::STATUS_APROVADO,
        ]);

        Ponto::create([
            'aluno_id'         => $aluno2->id,
            'empresa_id'       => $sescs->id,
            'nome_aluno'       => $aluno2->nome,
            'nome_empresa'     => $sescs->nome,
            'nome_instituicao' => $ufal->nome,
            'localizacao'      => '-9.6643, -35.7279',
            'status'           => Ponto::STATUS_PENDENTE,
        ]);

        $this->command->info('✅ Seed concluído! Credenciais de acesso:');
        $this->command->table(
            ['Tipo',          'E-mail',                      'Senha'],
            [
                ['Administrador', 'admin@projetoaprendiz.com', 'senha123'],
                ['Empresa',       'senac@senac.com',           'senha123'],
                ['Empresa',       'sescs@sescs.com',           'senha123'],
                ['Aluno',         'aluno1@aluno.com',          'senha123'],
                ['Aluno',         'aluno2@aluno.com',          'senha123'],
                ['Aluno',         'aluno3@aluno.com',          'senha123'],
            ]
        );
    }
}
