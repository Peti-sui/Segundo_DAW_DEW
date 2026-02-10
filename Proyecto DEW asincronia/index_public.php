<?php
/* Inicio de sesion para conservar estado de usuario */
/* Evita modificaciones en la logica solo se agregan comentarios */

/* Comprueba si ya existe una sesion iniciada y redirige al index en ese caso */
session_start();

if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

/* Carga el autoloader y configuraciones necesarias */
require_once 'config/autoload.php';

/* Obtiene el idioma actual mediante la funcion getIdiomaActual */
$idioma = getIdiomaActual();

/* Lee la cookie tema y asigna tema claro por defecto si no existe */
$tema = $_COOKIE['tema'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('tienda_titulo'); ?></title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <div class="preferencias-container text-center">
            <h1>𝜗𝜚 <?php echo __('tienda_titulo'); ?></h1>

            <div class="info-box mt-30">
                <h3><?php echo __('bienvenido'); ?></h3>
                <p><?php echo __('descripcion_tienda'); ?></p>

                <div class="d-flex gap-10 mt-20">
                    <a href="auth/login.php" class="w-100 text-center">࣪˖ <?php echo __('iniciar_sesion'); ?></a>
                    <a href="auth/registro.php" class="w-100 text-center">𐙚 <?php echo __('registrarse'); ?></a>
                </div>
            </div>

            <div class="info-box mt-20">
                <h4><?php echo __('caracteristicas'); ?></h4>
                <ul style="text-align: left; margin: 15px 0; padding-left: 20px;">
                    <li><?php echo __('caracteristica_1'); ?></li>
                    <li><?php echo __('caracteristica_2'); ?></li>
                    <li><?php echo __('caracteristica_3'); ?></li>
                    <li><?php echo __('caracteristica_4'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>