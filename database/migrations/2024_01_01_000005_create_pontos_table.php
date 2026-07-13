<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pontos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('nome_aluno', 60);
            $table->string('nome_empresa', 60);
            $table->string('nome_instituicao', 60);
            $table->string('localizacao', 255)->nullable();
            $table->tinyInteger('status')->default(0); // 0=pendente, 1=aprovado, 2=rejeitado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pontos');
    }
};
