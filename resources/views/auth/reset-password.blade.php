<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redefinir senha | Projeto Aprendiz</title>
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
            <p class="login-box-msg text-muted">Defina sua nova senha</p>

            @if($errors->any())
                <div class="alert alert-danger py-2"><small>{{ $errors->first() }}</small></div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="tipo" value="{{ $tipo }}">

                <div class="input-group mb-3">
                    <input type="email" name="email" value="{{ $email }}"
                           class="form-control" readonly>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control"
                           placeholder="Nova senha" required minlength="6">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Confirme a nova senha" required minlength="6">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-check mr-1"></i> Redefinir Senha
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
