<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Simulador Aduanal UPTex</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Fondo con la imagen uptex */
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url("{{ asset('assets/img/uptex.png') }}") no-repeat center center fixed;
            background-size: cover;
        }

        .login-card {
            background: rgba(31, 41, 55, 0.90);
            width: 100%;
            max-width: 450px;
            /* Tamaño ideal para un login centrado */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
            position: relative;
            backdrop-filter: blur(10px);
        }

        /* Barra superior decorativa */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #E53E3E 0%, #00843D 100%);
        }

        .login-content {
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
        }

        /* Contenedor para el logo y el título */
        .login-header {
            display: flex;
            align-items: center;
            justify-content: center;
            /* Centra el contenido del encabezado */
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo-interno {
            height: 55px;
            width: auto;
            object-fit: contain;
        }

        .login-content h2 {
            color: #ffffff;
            font-size: 1.7rem;
            font-weight: 700;
        }

        .subtitle {
            color: #ffffff;
            font-size: 0.85rem;
            text-align: center;
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background: #111827;
            border: 2px solid #374151;
            border-radius: 12px;
            color: #ffffff;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input:focus {
            border-color: #00843D;
            box-shadow: 0 0 0 4px rgba(0, 132, 61, 0.1);
        }

        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
            color: #ffb3b3;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.82rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: #00843D;
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            background: #00662e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 132, 61, 0.3);
        }

        .brand-tag {
            margin-top: 30px;
            font-size: 0.75rem;
            color: #ffffff;
            text-align: center;
            border-top: 1px solid #374151;
            padding-top: 20px;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="login-content">

            <div class="login-header">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="logo-interno">
                <h2>Iniciar Sesión</h2>
            </div>
            <p class="subtitle">Bienvenido al Simulador de Pedimentos Aduanales</p>

            @if(session('error') || $errors->any())
                <div class="error-msg">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Tu nombre de usuario"
                        required autofocus>
                </div>

                <div class="form-group" style="position: relative;">
                    <label>Contraseña</label>
                    <input type="password" id="login_password" name="password" placeholder="••••••••" required style="padding-right: 45px;">
                    <button type="button" onclick="togglePasswordVisibility('login_password', this)" style="position: absolute; right: 15px; top: 38px; background: none; border: none; cursor: pointer; color: #718096; font-size: 1.1rem; padding: 0;">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Entrar al Sistema
                </button>
            </form>

            <p class="brand-tag">
                <strong>UPTex</strong> &middot; Anexo 22 RGCE 2024
            </p>
        </div>
    </div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
</body>

</html>