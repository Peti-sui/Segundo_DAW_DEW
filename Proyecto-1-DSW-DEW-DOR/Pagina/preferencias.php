<?php
/* Inicializa la sesion */
session_start();

/* Determina el modo a mostrar:
   - Si viene por GET (después de procesarPreferencias), se usa eso
   - Si no, se lee la cookie
   - Por defecto, claro
*/
if(isset($_GET['modo'])){
    $modo = $_GET['modo'];
} elseif(isset($_COOKIE['preferencias'])) {
    $modo = json_decode($_COOKIE['preferencias'], true)['modo'] ?? 'claro';
} else {
    $modo = 'claro';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Preferencias - Brain Stuff</title>
    <link rel="stylesheet" href="./src/styles/paginaPrincipalEstilo.css">
</head>
<body class="<?= htmlspecialchars($modo) ?>">
<div class="pagina">
<header class="header">
    <a href="index.php"><img src="./src/IMG/Logo.png" alt="logo" style="width:90px;"></a>
    <nav class="barraArribaDerecha">
        <a href="./index.php">Inicio</a>
        <a href="./listaDeseos.php">Lista de Deseos</a>
        <?php if (isset($_SESSION['usuario'])): ?>
            <a href="./logout.php">Salir</a>
        <?php else: ?>
            <a href="./login.php">Iniciar Sesion</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <h2>Selecciona tus preferencias</h2>
    <form method="post" action="procesarPreferencias.php">
        <label for="modo">Estilo visual:</label>
        <select name="modo" id="modo">
            <option value="claro" <?php if($modo=='claro') echo 'selected'; ?>>Claro</option>
            <option value="oscuro" <?php if($modo=='oscuro') echo 'selected'; ?>>Oscuro</option>
        </select>
        <br>
        <button type="submit">Guardar preferencias</button>
    </form>
</main>

<footer class="footer">
    <p>© 2025|26 Brain Stuff (Kevin Pesao - Scoo - Negro - Colombiano). Todos los derechos reservados.</p>
</footer>
</div>
</body>
</html>
