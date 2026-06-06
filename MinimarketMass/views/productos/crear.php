<?php /* $error puede venir del controller si hubo un dato inválido */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><title>Nuevo producto · Mass</title>
<style>
  *{box-sizing:border-box;font-family:'Segoe UI',Arial,sans-serif}
  body{background:#f4f6f9;padding:30px}
  .card{max-width:460px;margin:auto;background:#fff;border-radius:12px;padding:26px;box-shadow:0 8px 25px rgba(0,0,0,.08)}
  h1{color:#0066B3;font-size:21px;margin-bottom:16px}
  label{display:block;font-size:13px;font-weight:600;margin:12px 0 4px}
  input,select{width:100%;padding:10px;border:1px solid #d7dde6;border-radius:8px;font-size:14px}
  button{width:100%;margin-top:18px;padding:11px;border:none;border-radius:8px;background:#0066B3;color:#fff;font-weight:700;font-size:15px;cursor:pointer}
  .error{background:#fef2f2;border:1px solid #f3c2c2;color:#b91c1c;padding:10px;border-radius:8px;font-size:13px;margin-bottom:8px}
  a{color:#0066B3;font-size:13px}
</style>
</head>
<body>
  <div class="card">
    <h1>Registrar nuevo producto</h1>
    <?php if (!empty($error)): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST" action="index.php?accion=guardar-producto">
      <label>Código de barras</label>
      <input type="text" name="codigo">
      <label>Nombre</label>
      <input type="text" name="nombre">
      <label>Marca</label>
      <input type="text" name="marca">
      <label>Categoría</label>
      <select name="categoria">
        <option value="1">Abarrotes</option>
        <option value="2">Bebidas</option>
        <option value="3">Lácteos</option>
        <option value="4">Limpieza</option>
        <option value="5">Aseo Personal</option>
        <option value="6">Panadería</option>
        <option value="7">Frutas y Verduras</option>
      </select>
      <label>Precio (S/)</label>
      <input type="number" step="0.10" name="precio">
      <label>Stock</label>
      <input type="number" name="stock">
      <button type="submit">Guardar producto</button>
    </form>
    <p style="margin-top:14px"><a href="index.php?accion=catalogo">← Volver al catálogo</a></p>
  </div>
</body>
</html>