<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Models\Aluno;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_away_from_admin_area(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_away_from_empresa_area(): void
    {
        $this->get(route('empresa.dashboard'))->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_away_from_aluno_area(): void
    {
        $this->get(route('aluno.dashboard'))->assertRedirect(route('login'));
    }

    public function test_logged_in_aluno_cannot_reach_admin_dashboard(): void
    {
        $aluno = Aluno::factory()->create();

        $this->actingAs($aluno, 'aluno')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_logged_in_empresa_cannot_reach_admin_dashboard(): void
    {
        $empresa = Empresa::factory()->create();

        $this->actingAs($empresa, 'empresa')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_logged_in_admin_cannot_reach_aluno_area(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'web')
            ->get(route('aluno.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_reaches_own_dashboard(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'web')
            ->get(route('admin.dashboard'))
            ->assertOk();
    }
}
