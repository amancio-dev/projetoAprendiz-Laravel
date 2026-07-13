<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Empresa;
use App\Models\Ponto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * O projeto PHP original (aprovar_ponto.php / excluir_ponto.php / editar_empresa_logada.php)
 * não verificava se o registro pertencia à empresa logada — bastava estar
 * autenticado como QUALQUER tipo de usuário e trocar o id na URL/GET.
 * Estes testes garantem que essa falha não existe na versão Laravel.
 */
class EmpresaOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_empresa_cannot_edit_another_empresas_aluno(): void
    {
        $empresaA = Empresa::factory()->create();
        $empresaB = Empresa::factory()->create();
        $alunoDaEmpresaB = Aluno::factory()->create(['empresa_id' => $empresaB->id]);

        $this->actingAs($empresaA, 'empresa')
            ->get(route('empresa.alunos.edit', $alunoDaEmpresaB))
            ->assertForbidden();
    }

    public function test_empresa_cannot_delete_another_empresas_aluno(): void
    {
        $empresaA = Empresa::factory()->create();
        $empresaB = Empresa::factory()->create();
        $alunoDaEmpresaB = Aluno::factory()->create(['empresa_id' => $empresaB->id]);

        $this->actingAs($empresaA, 'empresa')
            ->delete(route('empresa.alunos.destroy', $alunoDaEmpresaB))
            ->assertForbidden();

        $this->assertDatabaseHas('alunos', ['id' => $alunoDaEmpresaB->id]);
    }

    public function test_empresa_cannot_approve_another_empresas_ponto(): void
    {
        $empresaA = Empresa::factory()->create();
        $empresaB = Empresa::factory()->create();
        $alunoDeB = Aluno::factory()->create(['empresa_id' => $empresaB->id]);

        $ponto = Ponto::create([
            'aluno_id'         => $alunoDeB->id,
            'empresa_id'       => $empresaB->id,
            'nome_aluno'       => $alunoDeB->nome,
            'nome_empresa'     => $empresaB->nome,
            'nome_instituicao' => 'Instituto Teste',
            'localizacao'      => '-9.6643, -35.7279',
            'status'           => Ponto::STATUS_PENDENTE,
        ]);

        $this->actingAs($empresaA, 'empresa')
            ->patch(route('empresa.pontos.aprovar', $ponto))
            ->assertForbidden();

        $this->assertSame(Ponto::STATUS_PENDENTE, $ponto->fresh()->status);
    }

    public function test_empresa_can_edit_its_own_aluno(): void
    {
        $empresa = Empresa::factory()->create();
        $aluno = Aluno::factory()->create(['empresa_id' => $empresa->id]);

        $this->actingAs($empresa, 'empresa')
            ->get(route('empresa.alunos.edit', $aluno))
            ->assertOk();
    }
}
