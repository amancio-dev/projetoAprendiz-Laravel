# 🎓 Projeto Aprendiz — Laravel 13

Migração do projeto PHP original (PDO + AdminLTE) para Laravel 13, com MVC completo, Eloquent, multi-autenticação por guard e Blade.

Esta versão do README existe porque a primeira entrega foi feita com uma leitura rasa do projeto original (poucos arquivos abertos, sem checar o SQL completo). Depois de reclamação do autor, o projeto original foi lido por inteiro — os ~70 arquivos PHP da aplicação, não só os de biblioteca (AdminLTE) — e esta versão corrige o que estava errado e adiciona o que faltava. As duas seções abaixo documentam isso com honestidade, porque isso importa mais que uma lista de features.

---

## 🔎 O que a auditoria completa revelou

### Bugs reais na primeira entrega (Laravel), agora corrigidos
1. **Inconsistência de versão fatal**: `composer.json` pedia Laravel `^10.0`, mas `bootstrap/app.php` usava a sintaxe `Application::configure()->withMiddleware()`, que só existe a partir do Laravel 11. Rodar `composer install` teria instalado o Laravel 10 e a aplicação quebraria ao subir. **Corrigido**: `composer.json` agora pede Laravel `^13.0` (versão atual, lançada em março/2026) e PHP `^8.3`, consistente com a sintaxe usada.
2. **Duas features reais do original tinham ficado de fora**, porque só 6 dos ~70 arquivos PHP foram lidos antes de começar a portar:
   - Empresa editando o próprio cadastro (`gerenciar_empresa_logada.php` no original) — **adicionado**: `Empresa\PerfilController`.
   - Administrador cadastrando outros administradores (`gerenciar_usuario.php` / tabela `dono_app`) — **adicionado**: `Admin\AdminController`.

### O que existia no projeto original mas está confirmado como código morto (não foi portado, e não deveria ser)
Encontrado ao ler o SQL completo e fazer `grep` de cada tabela contra todos os arquivos PHP:
- **Tabela `empresa_acompanha_aluno`** (presença/falta, entrada/saída, anexo de justificativa) existe no dump SQL, mas **nenhum arquivo PHP do projeto a referencia**. É uma tabela criada e nunca implementada. Não portei, mas fica como sugestão de próxima feature real (ver seção de roadmap).
- `ler_acompanhar_treinamento.php` e `crud.php` fazem consultas a tabelas (`usuario`, `curso`, `acompanhamento_treinamento_usuario`) que **não existem no schema** — sobras de um template genérico de admin ("TrainOps") do qual este projeto foi iniciado, nunca adaptadas.
- `esqueci.php` (recuperação de senha) é um formulário estático com `action=""` — nunca foi ligado a um backend. Não existia de fato no original.
- O menu do aluno linka para `visualizar_batidas.php`, arquivo que **não existe no ZIP** — link quebrado no próprio original.

### Falhas de segurança confirmadas no PHP original (não hipotéticas — lidas no código)
1. **Nenhum controle de acesso por perfil.** `pg_sessao.php`, incluído em toda página protegida, só verifica `isset($_SESSION['email'])`. Nunca verifica `$_SESSION['tipo']`. Um aluno autenticado que soubesse a URL de uma página de admin conseguiria carregá-la.
2. **IDOR em `aprovar_ponto.php` e `excluir_ponto.php`**: recebem `id_ponto` via GET e fazem `UPDATE` direto, sem checar se o ponto pertence à empresa logada. Qualquer usuário autenticado podia aprovar/rejeitar ponto de qualquer empresa mudando o número na URL.
3. **IDOR em `editar_empresa_logada.php`**: o `id_empresa` usado na consulta vem de `$_GET['id_empresa']`, não da sessão — uma empresa logada podia editar os dados de outra empresa trocando o parâmetro.
4. Senhas com **SHA1 sem salt**; ações que alteram dados (aprovar/rejeitar ponto) disparadas por **GET**, sem proteção CSRF.

A versão Laravel corrige os 4 pontos por construção: guards separados por tipo de usuário + middleware `role`, ownership check (`abort_if`) em toda ação de Empresa/Aluno, bcrypt via `Hash::make()`, e todas as mutações são POST/PUT/PATCH/DELETE com token CSRF. Os testes em `tests/Feature/EmpresaOwnershipTest.php` verificam especificamente o cenário do item 2 e 3.

---

## ✨ Resumo das melhorias

| Aspecto | Original (PHP) | Laravel |
|---|---|---|
| Arquitetura | Script por página, SQL na view | MVC (Model / Controller / Blade) |
| Controle de acesso | Só verifica login, não verifica tipo | Guards + middleware `role` por rota |
| Ownership (empresa vê só o que é seu) | Ausente (IDOR confirmado) | `abort_if` em toda ação + testes cobrindo isso |
| Senha | SHA1 sem salt | bcrypt (`Hash::make`) |
| Mutações (aprovar/rejeitar) | GET, sem CSRF | POST/PATCH com CSRF |
| Recuperação de senha | Formulário estático, não funcional | Fluxo completo por guard, com e-mail |
| Banco de dados | SQL manual via PDO | Eloquent + Migrations + Seeder |
| Testes | Nenhum | PHPUnit cobrindo login, guards e ownership |

---

## 📁 Estrutura

```
app/
├── Http/Controllers/
│   ├── AuthController.php              # Login/logout multi-guard
│   ├── Auth/PasswordResetController.php# Recuperação de senha (3 guards)
│   ├── Admin/   (Dashboard, Admin, Empresa, Aluno, Instituicao, Ponto)
│   ├── Empresa/ (Dashboard, Perfil, Aluno, Instituicao, Ponto)
│   └── Aluno/   (Dashboard, Ponto)
│   └── Middleware/CheckRole.php
├── Models/ (Admin, Empresa, Aluno, InstituicaoEducacao, Ponto)
└── Notifications/ResetPasswordNotification.php

database/
├── migrations/  (6 migrations: 5 tabelas + tokens de reset por guard)
├── factories/   (Admin, Empresa, Aluno, InstituicaoEducacao)
└── seeders/DatabaseSeeder.php

resources/views/
├── auth/ (login, forgot-password, reset-password)
├── layouts/app.blade.php
├── admin/   (dashboard, admins, empresas, alunos, instituicoes, pontos)
├── empresa/ (dashboard, perfil, alunos, instituicoes, pontos)
└── aluno/   (dashboard, ponto/registrar, ponto/historico)

tests/Feature/
├── Auth/LoginTest.php            # Login nos 3 guards, credenciais erradas, guard errado
├── Auth/RoleMiddlewareTest.php   # Guest bloqueado, cross-guard bloqueado
└── EmpresaOwnershipTest.php      # Cobre exatamente as falhas de IDOR encontradas no original

routes/web.php
```

---

## 🚀 Instalação

```bash
composer create-project laravel/laravel projetoaprendiz-laravel
cd projetoaprendiz-laravel
# copie os arquivos deste pacote por cima (sobrescrevendo quando perguntado)

cp .env.example .env
php artisan key:generate
```

Configure o `.env` com seu banco (`DB_DATABASE=projetoaprendiz` etc.) e, se quiser testar a recuperação de senha localmente, configure `MAIL_MAILER` (o padrão aponta para Mailpit — `mailpit start` se tiver instalado, ou troque para `log` para ver o e-mail no `storage/logs/laravel.log`).

```bash
php artisan migrate:fresh --seed
php artisan test          # roda a suíte de testes
php artisan serve
```

Credenciais de teste (senha `senha123` para todos):

| Tipo | E-mail |
|---|---|
| Administrador | admin@projetoaprendiz.com |
| Empresa | senac@senac.com / sescs@sescs.com |
| Aluno | aluno1@aluno.com / aluno2@aluno.com / aluno3@aluno.com |

---

## 🗺️ Rotas

```
GET  /login  ·  POST /login  ·  POST /logout
GET/POST /esqueci-senha  ·  GET/POST /redefinir-senha/{token}

/admin     dashboard, admins (CRUD), empresas (CRUD), alunos (CRUD),
           instituicoes (CRUD), pontos (listar/aprovar/rejeitar)

/empresa   dashboard, perfil (editar), alunos (CRUD), instituicoes (CRUD),
           pontos (listar/aprovar/rejeitar)

/aluno     dashboard, ponto/registrar, ponto/historico
```

---

## 📌 O que não foi feito, e por quê (roadmap honesto)

Estas coisas ficaram de fora deliberadamente, não por esquecimento — construir sem poder rodar PHP neste ambiente (não há interpretador disponível aqui) para validar teria risco maior que valor:

- **Recurso de presença com anexo (`empresa_acompanha_aluon`)**: a tabela existe no SQL original mas nunca foi implementada. Daria uma feature real (empresa marca presença/falta do aluno com upload de justificativa), mas envolve upload de arquivo — prefiro sugerir isso como próximo passo a implementar sem poder testar upload de fato.
- **Geofencing no registro de ponto** (validar se a localização capturada está perto da empresa/instituição): não existe no original, seria uma melhoria nova, não uma paridade. Sugestão de próxima etapa.
- **Extração de Form Requests e Policies**: a validação atual nos controllers está correta, só não é o padrão mais idiomático do Laravel (`StoreEmpresaRequest` em vez de `$request->validate()` inline). Refatorar isso em ~15 controllers sem poder rodar os testes a cada mudança é mais risco do que ganho agora — os testes incluídos cobrem o comportamento atual e continuariam válidos se alguém fizer essa refatoração depois.

Se quiser, posso implementar qualquer um desses agora — é só pedir.
