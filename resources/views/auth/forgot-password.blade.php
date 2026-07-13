<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Esqueci minha senha | Projeto Aprendiz</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); }
        .login-box { margin-top: 8vh; }
        .login-logo a { color: #fff !important; font-size: 1.6rem; }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('login') }}"><i class="fas fa-graduation-cap mr-2"></i><b>Projeto Aprendiz</b></a>
    </div>

    <div class="card shadow-lg">
        <div class="card-body login-card-body">
            <p class="login-box-msg text-muted">Esqueceu sua senha? Informe seu e-mail e tipo de conta.</p>

            @if(session('success'))
                <div class="alert alert-success py-2"><small>{{ session('success') }}</small></div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger py-2"><small>{{ $errors->first() }}</small></div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="input-group mb-3">
                    <select name="tipo" class="form-control" required>
                        <option value="" disabled selected>Selecione o tipo de usuário</option>
                        <option value="aluno" @if(old('tipo')=='aluno') selected @endif>🎓 Aluno / Aprendiz</option>
                        <option value="empresa" @if(old('tipo')=='empresa') selected @endif>🏢 Empresa</option>
                        <option value="admin" @if(old('tipo')=='admin') selected @endif>🔑 Administrador</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-users"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control" placeholder="Seu e-mail" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane mr-1"></i> Enviar link de redefinição
                </button>
            </form>

            <p class="mt-3 mb-1 text-center">
                <a href="{{ route('login') }}"><i class="fas fa-arrow-left mr-1"></i>Voltar ao login</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
