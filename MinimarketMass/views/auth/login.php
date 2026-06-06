<?php /* Recibe $error desde AuthController */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ingreso · Minimarket Mass</title>
<style>
  *{box-sizing:border-box;font-family:'Segoe UI',Arial,sans-serif;margin:0;padding:0}
  body{min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0066B3,#004F8C)}
  .login{background:#fff;width:340px;border-radius:14px;padding:32px 28px;box-shadow:0 18px 45px rgba(0,0,0,.25)}
  .logo{display:block;text-align:center;background:#0066B3;color:#fff;font-weight:800;font-size:20px;letter-spacing:1px;padding:8px 0;border-radius:8px;margin-bottom:18px}
  label{display:block;font-size:13px;font-weight:600;margin:14px 0 5px}
  input{width:100%;padding:11px 13px;border:1px solid #d7dde6;border-radius:8px;font-size:14px}
  button{width:100%;margin-top:20px;padding:12px;border:none;border-radius:8px;background:#0066B3;color:#fff;font-size:15px;font-weight:700;cursor:pointer}
  .error{background:#fef2f2;border:1px solid #f3c2c2;color:#b91c1c;font-size:13px;padding:10px 12px;border-radius:8px;margin-bottom:12px}
  .success{background:#e8f5e9;border:1px solid #c8e6c9;color:#2e7d32;font-size:13px;padding:10px 12px;border-radius:8px;margin-bottom:12px;font-weight:bold;text-align:center}
  .hint{margin-top:16px;text-align:center;font-size:12px;color:#94a1b2}
</style>
</head>
<body>
  <div class="login">
    <span class="logo">MASS</span>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'finalizada'): ?>
        <div class="success">Sesión finalizada correctamente.</div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($error !== 'demasiados intentos' && (!isset($_SESSION['intentos']) || $_SESSION['intentos'] < 3)): ?>
        
        <form method="POST" action="index.php?accion=procesar-login">
          <label>Usuario</label>
          <input type="text" name="username" autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
          
          <label>Contraseña</label>
          <input type="password" name="password">
          
          <button type="submit">Ingresar</button>
        </form>
        
    <?php else: ?>
        <div style="text-align: center; margin-top: 15px;">
            <p style="color: #b91c1c; font-weight: bold; font-size: 14px; background: #fef2f2; padding: 10px; border-radius: 8px; border: 1px solid #f3c2c2;">
                Formulario bloqueado por seguridad.
            </p>
        </div>
    <?php endif; ?>

    <p class="hint">Demo: cajero01 / admin123</p>
  </div>
</body>
</html>