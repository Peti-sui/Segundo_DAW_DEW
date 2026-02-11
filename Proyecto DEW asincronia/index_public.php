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
    
    <!-- Leaflet CSS para el mapa de Madagascar -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        <!-- ---------------------------------------------------------------------- -->
        <!-- SECCION DE GEOLOCALIZACION MADAGASCAR -->
        <!-- Distribuidora oficial en Antananarivo capital de Madagascar -->
        <!-- ---------------------------------------------------------------------- -->
        <div class="madagascar-section mt-40">
            <div class="madagascar-header">
                <div class="madagascar-title-icon">𝜗𐙚</div>
                <h2 class="madagascar-title">Nuestra Distribuidora en Madagascar</h2>
            </div>
            
            <div class="madagascar-content">
                <!-- Columna izquierda informacion de contacto y direccion -->
                <div class="madagascar-info">
                    <div class="madagascar-card">
                        <div class="madagascar-card-icon">𝜗</div>
                        <h3>Direccion</h3>
                        <p>Antananarivo, Madagascar</p>
                        <p class="madagascar-detalle">Distrito Comercial Analakely</p>
                        <p class="madagascar-detalle">Av. de la Independencia, 45</p>
                    </div>
                    
                    <div class="madagascar-card">
                        <div class="madagascar-card-icon">𐙚</div>
                        <h3>Contacto</h3>
                        <p class="madagascar-telefono">+34 647 382 917</p>
                        <p class="madagascar-email">madagascar@tiendallaveros.com</p>
                    </div>
                    
                    <div class="madagascar-card">
                        <div class="madagascar-card-icon">𝜚</div>
                        <h3>Horario</h3>
                        <p class="madagascar-horario">Lunes a Viernes: 8:00 - 18:00</p>
                        <p class="madagascar-horario">Sabados: 9:00 - 14:00</p>
                        <p class="madagascar-horario madagascar-cerrado">Domingos: Cerrado</p>
                    </div>
                    
                    <div class="madagascar-buttons">
                        <a href="https://www.google.com/maps/search/?api=1&query=Antananarivo+Madagascar" 
                           target="_blank" class="madagascar-btn madagascar-btn-google">
                            <span>𝜗</span> Ver en Google Maps
                        </a>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=Antananarivo+Madagascar" 
                           target="_blank" class="madagascar-btn madagascar-btn-directions">
                            <span>𐙚</span> Como llegar
                        </a>
                    </div>
                </div>
                
                <!-- Columna derecha contenedor del mapa interactivo -->
                <div class="madagascar-map-container">
                    <div id="madagascar-map" class="madagascar-map"></div>
                    <div class="madagascar-map-caption">
                        <span>𝜗𐙚 Capital de Madagascar</span>
                    </div>
                </div>
            </div>
            
            <div class="madagascar-footer">
                <p>𝜗𝜚 Envios a toda la isla Distribucion nacional e internacional</p>
            </div>
        </div>
        <!-- ---------------------------------------------------------------------- -->
        <!-- FIN SECCION DE GEOLOCALIZACION MADAGASCAR -->
        <!-- ---------------------------------------------------------------------- -->
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="js/madagascar-map.js"></script>
</body>

</html>