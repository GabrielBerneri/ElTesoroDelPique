<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar — Admin</title>
    <link rel="stylesheet" href="/assets/css/estilos.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: var(--color-primario); }
        .login-card { background: var(--color-blanco); border-radius: 12px; padding: 40px; width: 100%; max-width: 400px; box-shadow: 0 8px 32px rgba(0,0,0,0.4); }
        .login-logo { text-align: center; margin-bottom: 28px; }
        .login-logo img { height: 80px; margin: 0 auto 12px; }
        .login-logo h1 { font-family: var(--fuente-titulo); font-size: 22px; color: var(--color-primario); }
        .campo { margin-bottom: 18px; }
        .campo label { display: block; font-size: 13px; font-weight: 700; color: var(--color-texto); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .campo input { width: 100%; padding: 12px 14px; border: 2px solid var(--color-borde); border-radius: 8px; font-size: 15px; transition: border-color 0.2s; }
        .campo input:focus { outline: none; border-color: var(--color-acento); }
        .error { background: #fee; border: 1px solid #fcc; color: #c00; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 18px; }
        .btn-login { width: 100%; padding: 14px; background: var(--color-acento); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
        .btn-login:hover { background: var(--color-acento-dark); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="/assets/image/logo.jpeg" alt="El Tesoro del Pique">
            <h1>Panel de administración</h1>
        </div>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/login">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Ingresar</button>
        </form>
    </div>
</body>
</html>
