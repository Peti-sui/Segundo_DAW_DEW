<?php
/* INICIO DE SESION PARA ACCEDER A VARIABLES DE SESION SI SON NECESARIAS */
session_start();

/* INICIALIZACION DEL ARREGLO QUE CONTENDRA LA LISTA DE DESEOS */
$listaDeseos = [];

/* VERIFICACION DE LA EXISTENCIA DE LA COOKIE QUE ALMACENA LA LISTA DE DESEOS */
if(isset($_COOKIE['listaDeseos'])){
    
    /* SE OBTIENE EL CONTENIDO RAW DE LA COOKIE */
    $raw = $_COOKIE['listaDeseos'];
    
    /* SE DECODIFICA EL FORMATO JSON A UN ARREGLO ASOCIATIVO */
    $decoded = json_decode($raw, true);
    
    /* SE VALIDA QUE LA DECODIFICACION SEA CORRECTA Y SE ASIGNA EL ARREGLO RESULTANTE */
    if(is_array($decoded)) $listaDeseos = $decoded;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- TITULO DEL DOCUMENTO HTML -->
    <title>Lista de Deseos</title>
    
    <!-- ENLACE A LA HOJA DE ESTILOS PRINCIPAL -->
    <link rel="stylesheet" href="./src/styles/paginaPrincipalEstilo.css">
</head>
<body>
<div class="pagina">

<header class="header">
    <!-- LOGO PRINCIPAL QUE REDIRIGE A LA PAGINA DE INICIO -->
    <a href="./index.php">
        <img src="./src/IMG/Logo.png" alt="logo" style="width:90px;">
    </a>
        <!-- 
        NAVEGACION SUPERIOR DERECHA CON ACCESOS A LISTA DE DESEOS,
        PREFERENCIAS Y LOGIN 
    -->
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

<main>
    <!-- TITULO PRINCIPAL DE LA PAGINA -->
    <h2>Lista de Deseos</h2>

    <?php if(empty($listaDeseos)): ?>
        
        <!-- MENSAJE MOSTRADO CUANDO LA LISTA DE DESEOS ESTA VACIA -->
        <p>No hay productos en la lista de deseos.</p>

    <?php else: ?>
        
        <!-- LISTA DE PRODUCTOS AGREGADOS A LA LISTA DE DESEOS -->
        <ul>
            <?php 
            /* VARIABLE PARA ACUMULAR EL TOTAL DE PRECIOS */
            $total = 0; 

            /* RECORRIDO DE CADA ELEMENTO DE LA LISTA DE DESEOS */
            foreach($listaDeseos as $item): ?>
                
                <!-- IMPRESION DEL NOMBRE Y PRECIO DEL PRODUCTO CON ESCAPADO DE SEGURIDAD -->
                <li>
    <?= htmlspecialchars($item['nombre']) ?> - €<?= number_format($item['precio'], 2) ?>
    
    <form method="post" action="procesarDeseos.php" style="display:inline;">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <button type="submit" name="accion" value="eliminar">Eliminar</button>
    </form>
</li>

            
            <?php 
            /* ACUMULACION DEL PRECIO DEL ITEM ACTUAL */
            $total += floatval($item['precio']); 
            endforeach; ?>
        </ul>

        <!-- IMPRESION DEL TOTAL FORMATEADO -->
        <p>Total: €<?= number_format($total, 2) ?></p>

        <!-- FORMULARIO PARA REALIZAR ACCIONES SOBRE LA LISTA, COMO VACIARLA -->
        <form method="post" action="procesarDeseos.php">
            <button type="submit" name="accion" value="vaciar">Vaciar Lista de Deseos</button>
        </form>

    <?php endif; ?>
</main>

<footer class="footer">
    <!-- PIE DE PAGINA CON DATOS DE COPYRIGHT -->
    <p>© 2025|26 Brain Stuff (Kevin Pesao - Scoo - Negro - Colombiano). Todos los derechos reservados.</p>
</footer>

</div>
</body>
</html>
