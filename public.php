<?php
// Vista pública read-only: lista archivos de un estudiante sin login
require_once __DIR__ . '/config/bd.php';

$uid   = isset($_GET['u']) ? (int)$_GET['u'] : 0;
$token = $_GET['k'] ?? '';

if ($uid <= 0 || !preg_match('/^[a-f0-9]{64}$/', $token)) {
    http_response_code(400); die('Link inválido');
}

// Validar token vigente
$stmt = $mysqli->prepare('SELECT 1 FROM tokens_publicos WHERE usuario_id=? AND token=? AND expira_en > NOW()');
$stmt->bind_param('is', $uid, $token);
$stmt->execute();
$valid = $stmt->get_result()->fetch_row();
$stmt->close();

if (!$valid) { http_response_code(403); die('Link expirado o inválido'); }

// Traer archivos del estudiante
$stmt = $mysqli->prepare('SELECT nombre_original, ruta, DATE(creado_en) AS fecha FROM archivos WHERE usuario_id=? ORDER BY creado_en DESC');
$stmt->bind_param('i', $uid);
$stmt->execute();
$res = $stmt->get_result();
$files = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Documentos (vista pública)</title>
<style>
  body{font-family:system-ui,Segoe UI,Arial,sans-serif;margin:20px;}
  table{border-collapse:collapse;width:100%}
  th,td{border:1px solid #ccc;padding:8px}
  thead{background:#f5f5f5}
</style>
</head>
<body>
<h2>Documentos del estudiante</h2>
<table>
  <thead><tr><th>Archivo</th><th>Fecha</th><th>Ver</th></tr></thead>
  <tbody>
  <?php if ($files): foreach($files as $f): ?>
    <tr>
      <td><?= htmlspecialchars($f['nombre_original']) ?></td>
      <td><?= htmlspecialchars($f['fecha']) ?></td>
      <td><?= htmlspecialchars($f[" target="_blank" rel="noopener">Abrir</a></td>
    </tr>
  <?php endforeach; else: ?>
    <tr><td colspan="3">Sin archivos</td></tr>
  <?php endif; ?>
  </tbody>
</table>
<p style="margin-top:12px;color:#666">Este enlace caduca automáticamente.</p>
</body>
</html>
