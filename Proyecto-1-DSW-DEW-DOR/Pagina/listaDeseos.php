<?php
/* Inicializa la sesion */
session_start();

$modo = isset($_COOKIE['preferencias']) ? json_decode($_COOKIE['preferencias'], true)['modo'] ?? 'claro' : 'claro';

/* Obtiene lista de deseos desde cookie */
$listaDeseos = [];
if(isset($_COOKIE['listaDeseos'])){
    $raw = $_COOKIE['listaDeseos'];
    $decoded = json_decode($raw, true);
    if(is_array($decoded)) $listaDeseos = $decoded;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Deseos - Brain Stuff</title>
    <link rel="stylesheet" href="./src/styles/paginaPrincipalEstilo.css">
</head>
<body class="<?= $modo ?>">
<div class="pagina">
<header class="header">
    <a href="./index.php"><img src="./src/IMG/Logo.png" alt="logo" style="width:90px;"></a>
    <nav class="barraArribaDerecha">
        <a href="./index.php">Inicio</a>
        <a href="./listaDeseos.php">Lista de Deseos</a>
        <a href="./preferencias.php">Preferencias</a>
        <?php if (isset($_SESSION['usuario'])): ?>
            <a href="./logout.php">Salir</a>
        <?php else: ?>
            <a href="./login.php">Iniciar Sesion</a>
        <?php endif; ?>
    </nav>
</header>
<main>
    <h2>Lista de Deseos</h2>
    <?php if(empty($listaDeseos)): ?>
        <p>No hay productos en la lista de deseos.</p>
    <?php else: ?>
        <ul>
            <?php 
            $total = 0;
            foreach($listaDeseos as $item): 
                $total += floatval($item['precio']);
            ?>
            <li>
                <?= htmlspecialchars($item['nombre']) ?> - €<?= number_format($item['precio'], 2) ?>
                <form method="post" action="procesarDeseos.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit" name="accion" value="eliminar">Eliminar</button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
        <p>Total: €<?= number_format($total, 2) ?></p>
        <form method="post" action="procesarDeseos.php">
            <button type="submit" name="accion" value="vaciar">Vaciar Lista de Deseos</button>
        </form>
    <?php endif; ?>
</main>
<footer class="footer">
    <p>© 2025|26 Brain Stuff (Kevin Pesao - Scoo - Negro - Colombiano). Todos los derechos reservados.</p>
</footer>
</div>
</body>
</html>
