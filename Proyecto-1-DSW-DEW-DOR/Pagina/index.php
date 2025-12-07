<?php
session_start();

/* 
    SE OBTIENEN LAS PREFERENCIAS DEL USUARIO DESDE LA COOKIE "PREFERENCIAS".
    SI LA COOKIE NO EXISTE, SE ASIGNA NULL.
*/
$preferencias = isset($_COOKIE['preferencias']) ? json_decode($_COOKIE['preferencias'], true) : null;

/* 
    SE DEFINE EL IDIOMA UTILIZADO EN LA INTERFAZ.
    SI NO EXISTE EN LAS PREFERENCIAS, SE ESTABLECE "ES" POR DEFECTO.
*/
$idioma = $preferencias['idioma'] ?? 'es';

/* 
    SE DEFINE EL MODO DE VISUALIZACION (CLARO / OSCURO).
    SI NO SE ENCUENTRA EN LAS PREFERENCIAS, SE ESTABLECE "CLARO".
*/
$modo = $preferencias['modo'] ?? 'claro';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- 
        TITULO PRINCIPAL DE LA PAGINA 
    -->
    <title>Brain Stuff</title>

    <!-- 
        HOJA DE ESTILOS PRINCIPAL PARA EL DISEÑO DE LA PAGINA 
    -->
    <link rel="stylesheet" href="./src/styles/paginaPrincipalEstilo.css">
</head>

<div class="pagina">
<header class="header">

    <!-- LOGO PRINCIPAL QUE REDIRIGE A LA PAGINA DE INICIO -->
    <a href="./index.php">
        <img src="./src/IMG/Logo.png" alt="logo" style=" width: 90px;">
    </a>

    <!-- 
        NAVEGACION SUPERIOR DERECHA CON ACCESOS A LISTA DE DESEOS,
        PREFERENCIAS Y LOGIN 
    -->
    <nav class="barraArribaDerecha">
    <a href="./index.php">Inicio</a>
    <a href="./listaDeseos.php">Lista de Deseos</a>
    <a href="./preferencias.php">Preferencias</a>
    <?php if (isset($_SESSION['usuario'])): ?>
        <a href="./logout.php">Logout</a>
    <?php else: ?>
        <a href="./login.php">Login</a>
    <?php endif; ?>
</nav>
</header>

<body>

<?php if (isset($_SESSION['usuario'])): ?>
        <?php if (!empty($_SESSION['esAdmin'])): ?>
            <div class="bienvenidaAdmin">
                <strong>Bienvenido, Administrador 👑</strong>
            </div>
        <?php else: ?>
            <div class="bienvenidaUsuario">
                <strong>Bienvenido/a, <?php echo htmlspecialchars($_SESSION['usuario']); ?> !</strong>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- CONTENEDOR DINAMICO PARA LA VISUALIZACION DE PRODUCTOS -->
    <div class="muestraProductos"></div>

    <!-- SECCION DESTINADA A MOSTRAR EL RESUMEN DEL CARRITO -->
    <div class="resumenCarrito"></div>

    <!-- SECCION PARA MOSTRAR EL RESUMEN DE DESEOS -->
    <div class="resumenDeseos"></div>

    

</body>

<footer class="footer">
    <!-- INFORMACION LEGAL Y DERECHOS RESERVADOS -->
    <p>© 2025|26 Brain Stuff (Kevin Pesao - Scoo - Negro - Colombiano). Todos los derechos reservados.</p>
</footer>
</div>

<!-- ARCHIVOS JAVASCRIPT QUE MANEJAN FUNCIONALIDADES DINAMICAS -->
<script src="./src/js/productos.js"></script>
<script src="./src/js/carrito.js"></script>
<script src="./src/js/deseos.js"></script>
<script src="./src/js/preferencias.js"></script>
</html>
