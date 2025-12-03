<?php
session_start();

$preferencias = isset($_COOKIE['preferencias']) ? json_decode($_COOKIE['preferencias'], true) : null;

$idioma = $preferencias['idioma'] ?? 'es';
$modo = $preferencias['modo'] ?? 'claro';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brain Stuff</title>
    <link rel="stylesheet" href="./src/styles/paginaPrincipalEstilo.css">
</head>
<div class="pagina">
<header class="header">
    <a href="./index.php">
        <img src="./src/IMG/Logo.png" alt="logo" style=" width: 90px;">
    </a>
    <nav class="barraArribaDerecha">
        <a href="./listaDeseos.php">Lista de Deseos</a>
        <a href="./preferencias.php">Preferencias</a>
        <a href="./login.php">Login</a>
    </nav>
</header>
<body>
    <div class="muestraProductos"></div>
    <div class="resumenCarrito"></div>
</body>
<footer class="footer">
    <p>© 2025|26 Brain Stuff (Kevin Pesao - Scoo - Negro - Colombiano). Todos los derechos reservados.</p>
</footer>
</div>
<script src="./src/js/productos.js"></script>
<script src="./src/js/carrito.js"></script>
<script src="./src/js/preferencias.js"></script>
</html>