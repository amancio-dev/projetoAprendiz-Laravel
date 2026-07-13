<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Models\Aluno;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders(): void
    {
        $this->get(route('login'))->assertOk();
    }

    public function test_admin_can_login_and_reaches_admin_dashboard(): void
    {
        $admin = Admin::factory()->create(['password' => Hash::make('senha123')]);

        $response = $this->post(route('login.post'), [
            'tipo'     => '3',
            'email'    => $admin->email,
            'password' => 'senha123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'web');
    }

    public function test_empresa_can_login_and_reaches_empresa_dashboard(): void
    {
        $empresa = Empresa::factory()->create(['password' => Hash::make('senha123')]);

        $response = $this->post(route('login.post'), [
            'tipo'     => '2',
            'email'    => $empresa->email,
            'password' => 'senha123',
        ]);

        $response->assertRedirect(route('empresa.dashboard'));
        $this->assertAuthenticatedAs($empresa, 'empresa');
    }

    public function test_aluno_can_login_and_reaches_aluno_dashboard(): void
    {
        $aluno = Aluno::factory()->create(['password' => Hash::make('senha123')]);

        $response = $this->post(route('login.post'), [
            'tipo'     => '1',
            'email'    => $aluno->email,
            'password' => 'senha123',
        ]);

        $response->assertRedirect(route('aluno.dashboard'));
        $this->assertAuthenticatedAs($aluno, 'aluno');
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $aluno = Aluno::factory()->create(['password' => Hash::make('senha123')]);

        $response = $this->post(route('login.post'), [
            'tipo'     => '1',
            'email'    => $aluno->email,
            'password' => 'senha-errada',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('aluno');
    }

    public function test_a_student_cannot_login_through_the_company_guard(): void
    {
        // Garante que o "tipo" escolhido no formulário realmente determina
        // o guard usado — um aluno não pode logar como se fosse empresa,
        // mesmo com credenciais de aluno corretas.
        $aluno = Aluno::factory()->create(['password' => Hash::make('senha123')]);

        $response = $this->post(route('login.post'), [
            'tipo'     => '2',
            'email'    => $aluno->email,
            'password' => 'senha123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('empresa');
    }
}
