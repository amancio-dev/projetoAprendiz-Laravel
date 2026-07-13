<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Projeto Aprendiz</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); }
        .login-box { margin-top: 5vh; }
        .login-logo a { color: #fff !important; font-size: 1.8rem; }
        .login-card-body { border-radius: 8px; }
        .btn-login { background: #0f3460; border-color: #0f3460; font-weight: 600; }
        .btn-login:hover { background: #1a5276; border-color: #1a5276; }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><i class="fas fa-graduation-cap mr-2"></i><b>Projeto Aprendiz</b></a>
    </div>

    <div class="card shadow-lg">
        <div class="card-body login-card-body">
            <p class="login-box-msg text-muted">Faça login para continuar</p>

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    <small>{{ $errors->first() }}</small>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-warning py-2">
                    <small>{{ session('error') }}</small>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                {{-- Tipo de usuário --}}
                <div class="input-group mb-3">
                    <select name="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                        <option value="" disabled @if(!old('tipo')) selected @endif>Selecione o tipo de usuário</option>
                        <option value="1" @if(old('tipo')=='1') selected @endif>🎓 Aluno / Aprendiz</option>
                        <option value="2" @if(old('tipo')=='2') selected @endif>🏢 Empresa</option>
                        <option value="3" @if(old('tipo')=='3') selected @endif>🔑 Administrador</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-users"></span></div>
                    </div>
                </div>

                {{-- E-mail --}}
                <div class="input-group mb-3">
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="E-mail" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>

                {{-- Senha --}}
                <div class="input-group mb-3">
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Senha" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-login btn-block text-white">
                            <i class="fas fa-sign-in-alt mr-1"></i> Acessar
                        </button>
                    </div>
                </div>
            </form>

            <p class="mt-3 mb-1 text-center">
                <a href="{{ route('password.request') }}"><i class="fas fa-key mr-1"></i>Esqueci minha senha</a>
            </p>
            <p class="mt-1 mb-1 text-center text-muted">
                <small>Dúvidas? Entre em contato com o administrador.</small>
            </p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
