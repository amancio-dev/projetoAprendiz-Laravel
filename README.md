![Projeto Aprendiz — Amancio.dev](.github/assets/cover.svg?v=b2aa67ce59)

<div align="center">

# Projeto Aprendiz

**Gerenciamento de aprendizes, controle de presença e autenticação por perfil**

![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat-square&logo=mysql&logoColor=white)
![PHPUnit](https://img.shields.io/badge/Testes-PHPUnit-3C9CD7?style=flat-square)

</div>

[Visão geral](#visão-geral) · [Tecnologias](#tecnologias) · [Funcionalidades](#funcionalidades) · [Decisões técnicas](#decisões-técnicas) · [Como executar](#como-executar) · [Testes](#testes) · [Estrutura](#estrutura) · [Documentação](#documentação-adicional)

## Visão geral

O Projeto Aprendiz é um sistema web para organizar jovens aprendizes, empresas e instituições de ensino. Empresas acompanham os próprios aprendizes e aprovam registros de presença; aprendizes consultam seu histórico e podem registrar a localização capturada pelo navegador.

A aplicação nasceu em PHP com PDO e foi migrada para Laravel como exercício de modernização de código legado. A evolução inclui organização em MVC, Eloquent ORM, autenticação por perfil e tratamento de falhas de autorização e armazenamento de senhas identificadas na versão original.

## Tecnologias

| Tecnologia | Uso no projeto |
| --- | --- |
| PHP 8.3+ e Laravel 13 | Back-end e estrutura MVC |
| Eloquent ORM e MySQL 8+ | Modelos, relacionamentos e persistência |
| Autenticação com múltiplos guards | Perfis de administrador, empresa e aprendiz |
| Blade e AdminLTE 3 | Templates e interface administrativa; AdminLTE via CDN |
| DataTables | Busca e paginação de tabelas, via CDN |
| HTML5 Geolocation e Leaflet.js | Captura de localização e mapa interativo |
| OpenStreetMap | Tiles do mapa, sem API key |
| PHPUnit | Testes automatizados |

## Funcionalidades

| Perfil | Recursos |
| --- | --- |
| Administrador | Dashboard; cadastro, consulta, edição e exclusão de empresas, aprendizes e instituições; gestão de administradores; aprovação e rejeição de pontos |
| Empresa | Dashboard dos próprios aprendizes; gestão de aprendizes e instituições vinculados à empresa; aprovação e rejeição de seus pontos; edição de perfil e credenciais |
| Aprendiz | Registro de presença com opção de capturar localização; mapa com a posição capturada; histórico com filtro por status pendente, aprovado ou rejeitado |

## Decisões técnicas

- **Autenticação por perfil:** os guards `web`, `empresa` e `aluno` estão configurados com provedores próprios. O middleware `role` verifica o perfil nas rotas.
- **Autorização por vínculo:** operações da empresa verificam a relação entre a conta autenticada e o registro solicitado. A suíte inclui tentativas de editar, excluir e aprovar registros de outra empresa.
- **Senhas e recuperação de acesso:** senhas usam hash via `Hash::make()`; a recuperação utiliza brokers e tabelas de tokens separados por perfil.
- **Geolocalização no navegador:** HTML5 Geolocation captura as coordenadas mediante permissão do usuário; Leaflet e OpenStreetMap mostram a posição no mapa.

### Evolução em relação ao PHP original

| Aspecto | Implementação original | Implementação Laravel |
| --- | --- | --- |
| Acesso por perfil | Verificação apenas de login, sem conferir o tipo de conta | Guards configurados por perfil e middleware nas rotas |
| Aprovação de ponto | Identificador recebido por GET sem conferir o vínculo com a empresa | Verificação do vínculo do ponto com a empresa autenticada |
| Edição de empresa | Identificador recebido pelo request | Empresa obtida pelo guard autenticado |
| Armazenamento de senhas | SHA-1 sem salt | Hash de senha pelo mecanismo do Laravel |

## Como executar

### Pré-requisitos

- PHP >= 8.3
- Composer
- MySQL >= 8.0

### Instalação local

~~~bash
git clone https://github.com/amancio-dev/projetoAprendiz-Laravel.git
cd projetoAprendiz-Laravel

composer install
cp .env.example .env
php artisan key:generate
~~~

Configure o banco no `.env`:

~~~dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projetoaprendiz
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
~~~

Crie o banco:

~~~bash
mysql -u seu_usuario -p -e "CREATE DATABASE projetoaprendiz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
~~~

No banco local de demonstração, o comando abaixo recria as tabelas e adiciona os dados de exemplo:

~~~bash
php artisan migrate:fresh --seed
php artisan serve
~~~

Acesse [http://127.0.0.1:8000](http://127.0.0.1:8000).

> Para MySQL/MariaDB mais antigo: se ocorrer erro de collation, ajuste `utf8mb4_0900_ai_ci` para `utf8mb4_unicode_ci` na conexão MySQL de `config/database.php`.

<details>
<summary><strong>Acessos já definidos no seeder para demonstração local</strong></summary>

As contas abaixo pertencem ao ambiente de desenvolvimento criado pelo seeder.

| Tipo | E-mail | Senha |
| --- | --- | --- |
| Administrador | admin@projetoaprendiz.com | senha123 |
| Empresa | senac@senac.com | senha123 |
| Empresa | sescs@sescs.com | senha123 |
| Aprendiz | aluno1@aluno.com | senha123 |
| Aprendiz | aluno2@aluno.com | senha123 |
| Aprendiz | aluno3@aluno.com | senha123 |

</details>

## Testes

~~~bash
php artisan test
~~~

Entre os cenários presentes na suíte estão:

| Arquivo | Cenários |
| --- | --- |
| `tests/Feature/Auth/LoginTest.php` | Login nos guards, senha incorreta e tentativa de login com perfil incompatível |
| `tests/Feature/Auth/RoleMiddlewareTest.php` | Acesso de visitante e tentativas de acessar área de outro perfil |
| `tests/Feature/EmpresaOwnershipTest.php` | Edição, exclusão e aprovação de registros de outra empresa; acesso aos próprios registros |

A suíte também contém testes em outras áreas, como autenticação, configurações de conta e equipes. Execute o comando para verificar o resultado da versão instalada.

## Estrutura

A estrutura abaixo destaca as áreas principais do domínio, sem listar todos os arquivos:

~~~text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── Auth/PasswordResetController.php
│   │   ├── Admin/
│   │   ├── Empresa/
│   │   └── Aluno/
│   └── Middleware/CheckRole.php
├── Models/
│   └── Admin · Empresa · Aluno · InstituicaoEducacao · Ponto
└── Notifications/ResetPasswordNotification.php

database/
├── migrations/
├── factories/
└── seeders/DatabaseSeeder.php

resources/views/
├── auth/
├── layouts/app.blade.php
├── admin/
├── empresa/
└── aluno/

routes/web.php
tests/Feature/
~~~

## Documentação adicional

<details>
<summary><strong>Rotas principais</strong></summary>

| Rota | Uso |
| --- | --- |
| `/` | Redirecionamento para login |
| `/login` | Seleção de perfil e credenciais |
| `/esqueci-senha` | Recuperação de senha |
| `/redefinir-senha/{token}` | Formulário de nova senha |
| `/admin/dashboard` | Painel do administrador |
| `/admin/admins` | Gestão de administradores |
| `/admin/empresas` | Gestão de empresas |
| `/admin/alunos` | Gestão de aprendizes |
| `/admin/instituicoes` | Gestão de instituições |
| `/admin/pontos` | Listagem e aprovação de pontos |
| `/empresa/dashboard` | Painel da empresa |
| `/empresa/perfil` | Edição do próprio cadastro |
| `/empresa/alunos` | Gestão dos próprios aprendizes |
| `/empresa/instituicoes` | Gestão de instituições vinculadas |
| `/empresa/pontos` | Aprovação dos pontos da empresa |
| `/aluno/dashboard` | Painel do aprendiz |
| `/aluno/ponto/registrar` | Registro de presença |
| `/aluno/ponto/historico` | Histórico de pontos |

</details>

### Contribuição

1. Faça um fork do projeto.
2. Crie uma branch: `git checkout -b feature/minha-feature`.
3. Faça commit: `git commit -m 'feat: minha feature'`.
4. Envie a branch: `git push origin feature/minha-feature`.
5. Abra um Pull Request.

## Licença

Este repositório ainda não contém um arquivo de licença. Consulte o responsável pelo projeto antes de reutilizar ou distribuir o código.
