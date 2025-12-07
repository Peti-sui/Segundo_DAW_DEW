<?php
session_start();

$mensaje = "";
// Procesar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    // Validación simple
    if ($usuario === 'admin' && $clave === '1234') {
        $_SESSION['usuario'] = 'admin';
        $_SESSION['esAdmin'] = true;
        $mensaje = "¡Bienvenido admin!";
        header("Location: index.php");
        exit;
    } elseif ($usuario && $clave) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['esAdmin'] = false;
        $mensaje = "Bienvenido/a " . htmlspecialchars($usuario);
        header("Location: index.php");
        exit;
    } else {
        $mensaje = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login - Brain Stuff</title>
    <link rel="stylesheet" href="./src/styles/paginaPrincipalEstilo.css">
</head>
<body>
<div class="pagina">
<header class="header">
    <a href="index.php"><img src="./src/IMG/Logo.png" alt="logo" style=" width:90px;"></a>
</header>

<main>
    <h2>Iniciar Sesión</h2>
    <?php if ($mensaje): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Usuario:</label>
        <input type="text" name="usuario" required>
        <br>
        <label>Contraseña:</label>
        <input type="password" name="clave" required>
        <br>
        <button type="submit">Entrar</button>
    </form>
</main>

<footer class="footer">
    <!-- INFORMACION LEGAL Y DERECHOS RESERVADOS -->
    <p>© 2025|26 Brain Stuff (Kevin Pesao - Scoo - Negro - Colombiano). Todos los derechos reservados.</p>
</footer>
</div>
</body>
</html>