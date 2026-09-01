<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - Parque Ambiental</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fuente e íconos (opcional, pero da buen aspecto) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 420px;
        }
        .login-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            color: #718096;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.25rem;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            transition: border 0.2s;
            margin-bottom: 1.25rem;
            background: #f7fafc;
        }
        .form-control:focus {
            outline: none;
            border-color: #2b6cb0;
            box-shadow: 0 0 0 3px rgba(43,108,176,0.1);
            background: white;
        }
        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            background: #2b6cb0;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }
        .btn-primary:hover {
            background: #1a4f7a;
        }
        .test-users {
            margin-top: 2rem;
            border-top: 1px solid #e2e8f0;
            padding-top: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #4a5568;
        }
        .test-users div {
            background: #f7fafc;
            padding: 0.5rem;
            border-radius: 0.5rem;
            text-align: center;
        }
        .test-users .email {
            font-weight: 600;
            color: #2b6cb0;
        }
        .test-users .pass {
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">Iniciar sesión</h1>
        <p class="login-subtitle">Ingresá con tus credenciales institucionales</p>

        <!-- Mensaje de error (si falla el login) -->
        @if ($errors->any())
            <div style="background: #fed7d7; color: #9b2c2c; padding: 0.75rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email" class="form-label">CORREO ELECTRÓNICO</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" placeholder="usuario@parque.gob">

            <label for="password" class="form-label">CONTRASEÑA</label>
            <input id="password" type="password" name="password" required class="form-control" placeholder="********">

            <button type="submit" class="btn-primary">
                Ingresar al sistema
            </button>
        </form>

        <!-- Usuarios de prueba (tal cual la imagen) -->
        <div class="test-users">
            <div>
                <div class="email">admin@parque.gob</div>
                <div class="pass">admin123</div>
            </div>
            <div>
                <div class="email">laura@parque.gob</div>
                <div class="pass">emp123</div>
            </div>
        </div>
    </div>
</body>
</html>