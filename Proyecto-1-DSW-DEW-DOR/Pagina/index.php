<?php
/* Inicializa la sesion */
session_start();

/* Lee las preferencias desde cookie */
$preferencias = isset($_COOKIE['preferencias']) ? json_decode($_COOKIE['preferencias'], true) : [];
$modo = $preferencias['modo'] ?? 'claro';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brain Stuff</title>
    <link rel="stylesheet" href="./src/styles/paginaPrincipalEstilo.css">
</head>
<body class="<?= $modo ?>">
<div class="pagina">
<header class="header">
    <a href="./index.php">
        <img src="./src/IMG/Logo.png" alt="logo" style="width: 90px;">
    </a>
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

<?php if (isset($_SESSION['usuario'])): ?>
    <?php if (!empty($_SESSION['esAdmin'])): ?>
        <div class="bienvenidaAdmin">
            <strong>Bienvenido, gran administrador todopoderoso de la historia 👑</strong>
        </div>
    <?php else: ?>
        <div class="bienvenidaUsuario">
            <strong>
                Bienvenid@, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!!! Disfruta del contenido en venta exclusivo para alternativos como tu!!!
            </strong>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="layout-tienda">
    <div class="muestraProductos"></div>

    <aside class="panelLateral">
        <div class="resumenCarrito"></div>
        <div class="resumenDeseos"></div>
    </aside>
</div>

<footer class="footer">
    <p>© 2025|26 Brain Stuff (Kevin Pesao - Scoo - Negro - Colombiano). Todos los derechos reservados.</p>
</footer>
</div>

<script src="./src/js/productos.js"></script>
<script src="./src/js/carrito.js"></script>
<script src="./src/js/deseos.js"></script>
<script src="./src/js/preferencias.js"></script>
</body>
</html>
