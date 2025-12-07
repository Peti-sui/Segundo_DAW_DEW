<?php
/* Inicializa la sesion */
session_start();

$modo = isset($_COOKIE['preferencias']) ? json_decode($_COOKIE['preferencias'], true)['modo'] ?? 'claro' : 'claro';

$compraRealizada = false;
$resumen = "";
$fecha = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrito'])) {
    $carrito = json_decode($_POST['carrito'], true);
    $total = 0;
    $resumen = "<ul>";
    foreach ($carrito as $item) {
        $linea = htmlspecialchars($item['nombre']) . " x " . intval($item['cantidad']) . " = €" . number_format($item['precio'] * $item['cantidad'],2);
        $resumen .= "<li>$linea</li>";
        $total += $item['precio'] * $item['cantidad'];
    }
    $resumen .= "</ul>";
    $fecha = date("d-m-Y H:i:s");
    $_SESSION['ultimaCompra'] = $fecha;
    $compraRealizada = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Checkout - Brain Stuff</title>
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
    <h2>Tu compra ha sido extremadamente exitosa!!</h2>
    <?php if ($compraRealizada): ?>
        <p><strong>Fecha de compra:</strong> <?= htmlspecialchars($fecha) ?></p>
        <h3>Resumen de productos:</h3>
        <?= $resumen ?>
        <p><strong>Total: €<?= number_format($total, 2) ?></strong></p>
        <script>
            localStorage.removeItem('carrito');
        </script>
    <?php else: ?>
        <p>No hay datos de compra para mostrar.</p>
    <?php endif; ?>
</main>
<footer class="footer">
    <p>© 2025|26 Brain Stuff (Kevin Pesao - Scoo - Negro - Colombiano). Todos los derechos reservados.</p>
</footer>
</div>
</body>
</html>
