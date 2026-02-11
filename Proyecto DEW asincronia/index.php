<?php
/* Inicio de sesion y comprobacion de usuario autenticado */
/* Se inicia la sesion y se redirige a index publico si no hay id en sesion */
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index_public.php");
    exit;
}

/* Carga de dependencias y conexion a la base de datos */
/* Se incluyen los archivos necesarios para autoload y conexion a DB */
require_once 'config/autoload.php';
require_once 'config/db.php';

/* Obtencion de idioma y tema del usuario */
/* Se obtiene el idioma actual para las etiquetas de la interfaz */
$idioma = getIdiomaActual();
/* Se obtiene el tema desde la cookie o se asigna claro por defecto */
$tema = $_COOKIE['tema'] ?? 'claro';

/* Obtener todos los productos usando el modelo Producto */
/* Se almacena la coleccion completa de productos en un array */
$productos = Producto::getAll();

/* Separar productos por tipo: oferta (bolso) y premium (mochila) */
$productosOferta = [];
$productosPremium = [];
$otrosProductos = [];

foreach ($productos as $producto) {
    /* Clasificar producto segun su tipo en arrays separados */
    if ($producto->getTipo() == 'bolso') {
        $productosOferta[] = $producto;
    } elseif ($producto->getTipo() == 'mochila') {
        $productosPremium[] = $producto;
    } else {
        $otrosProductos[] = $producto;
    }
}

/* Combinar productos destacados: 2 oferta, 2 premium y repetir */
/* Se mezclan productos de oferta y premium en un array principal */
$productosDestacados = [];
$maxCount = max(count($productosOferta), count($productosPremium));
for ($i = 0; $i < $maxCount; $i++) {
    if (isset($productosOferta[$i])) {
        $productosDestacados[] = $productosOferta[$i];
    }
    if (isset($productosPremium[$i])) {
        $productosDestacados[] = $productosPremium[$i];
    }
}

/* Si no hay suficientes productos destacados, añadir otros productos adicionales */
if (count($productosDestacados) < 4) {
    foreach ($otrosProductos as $producto) {
        if (count($productosDestacados) >= 8) break;
        $productosDestacados[] = $producto;
    }
}

/* Id del usuario actual obtenido desde la sesion */
$idUsuario = $_SESSION['id'];

/* Obtener ids de productos que ya estan en el carrito del usuario */
/* Consulta directa a la base de datos y almacenamiento en array */
$carritoIds = [];
$carritoRes = $conn->query(
    "SELECT producto_id FROM carrito WHERE usuario_id = $idUsuario"
);
while ($row = $carritoRes->fetch_assoc()) {
    $carritoIds[] = $row['producto_id'];
}

/* Obtener ids de productos que ya estan en la lista de deseos del usuario */
/* Consulta directa a la base de datos y almacenamiento en array */
$deseosIds = [];
$deseosRes = $conn->query(
    "SELECT producto_id FROM lista_deseos WHERE usuario_id = $idUsuario"
);
while ($row = $deseosRes->fetch_assoc()) {
    $deseosIds[] = $row['producto_id'];
}

/* Contar pedidos pendientes del usuario */
/* Se realiza consulta COUNT para obtener total de pedidos pendientes */
$pedidosPendientes = 0;
$pendientesRes = $conn->query(
    "SELECT COUNT(*) as total FROM pedidos 
     WHERE usuario_id = $idUsuario AND estado = 'pendiente'"
);
if ($pendientesRes) {
    $pendientesData = $pendientesRes->fetch_assoc();
    $pedidosPendientes = $pendientesData['total'];
}

/* Contar elementos en lista de deseos usando el array previamente obtenido */
$totalDeseos = count($deseosIds);
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
        <h1><?php echo __('tienda_titulo'); ?></h1>

        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <div class="admin-notice">
                <p>𝜗𝜚 <?php echo __('bienvenido_admin'); ?>,
                    <strong><?= htmlspecialchars($_SESSION['usuario'] ?? 'Admin') ?></strong></p>
            </div>
        <?php else: ?>
            <div class="info-box">
                <p>𝜗𝜚 <?php echo __('bienvenido_usuario'); ?>,
                    <strong><?= htmlspecialchars($_SESSION['usuario'] ?? 'Usuario') ?></strong></p>
            </div>
        <?php endif; ?>

        <div class="nav-links">
            <a href="carrito/ver.php">𝜗𝜚 <?php echo __('ver_carrito'); ?></a>
            <a href="deseos/ver.php">
                𝜗𝜚 <?php echo __('lista_deseos'); ?>
                <?php if ($totalDeseos > 0): ?>
                    <span class="badge badge-wish"><?= $totalDeseos ?></span>
                <?php endif; ?>
            </a>
            <a href="carrito/mis_pedidos.php">
                𝜗𝜚 <?php echo __('mis_pedidos'); ?>
                <?php if ($pedidosPendientes > 0): ?>
                    <span class="badge badge-warning"><?= $pedidosPendientes ?></span>
                <?php endif; ?>
            </a>
            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <a href="admin/index.php">𝜗𝜚 <?php echo __('panel_admin'); ?></a>
                <a href="admin/pedidos.php">𝜗𝜚 <?php echo __('gestion_pedidos'); ?></a>
            <?php endif; ?>
            <a href="preferencias.php">𝜗𝜚 <?php echo __('preferencias'); ?></a>
            <a href="auth/logout.php">𝜗𝜚 <?php echo __('salir'); ?></a>
        </div>

        <div class="info-box mt-20">
            <h3><?php echo __('productos'); ?></h3>
            <p><strong><?php echo __('llaves'); ?>:</strong> <?php echo __('precio'); ?> normal × cantidad</p>
            <p><strong><?php echo __('bolso'); ?>:</strong> <?php echo __('precio'); ?> fijo (oferta especial)</p>
            <p><strong><?php echo __('mochila'); ?>:</strong> <?php echo __('precio'); ?> doble (producto premium)</p>
        </div>

        <!-- CARRUSEL DE PRODUCTOS DESTACADOS -->
        <?php if (count($productosDestacados) > 0): ?>
        <div class="carousel-container mt-20">
            <div class="carousel-controls">
                <button class="carousel-btn prev" aria-label="<?php echo __('anterior'); ?>">‹</button>
                <div class="carousel-indicators"></div>
                <button class="carousel-btn next" aria-label="<?php echo __('siguiente'); ?>">›</button>
            </div>
            
            <div class="carousel-wrapper">
                <div class="carousel-track">
                    <?php foreach ($productosDestacados as $producto): 
                        /* Verificar si producto ya esta en carrito o lista de deseos */
                        $yaEnCarrito = in_array($producto->getId(), $carritoIds);
                        $yaEnDeseos = in_array($producto->getId(), $deseosIds);
                        ?>
                        <div class="carousel-slide producto" data-product-id="<?= $producto->getId() ?>">
                            <div class="carousel-product-inner">
                                <h3 class="d-flex justify-between align-center">
                                    <?= htmlspecialchars($producto->getNombre()) ?>
                                    <?php if ($producto->getTipo() == 'bolso'): ?>
                                        <span class="badge badge-oferta"><?php echo __('oferta'); ?></span>
                                    <?php elseif ($producto->getTipo() == 'mochila'): ?>
                                        <span class="badge badge-premium">PREMIUM</span>
                                    <?php endif; ?>
                                </h3>

                                <p class="precio">
                                    <strong><?= number_format($producto->getPrecio(), 2) ?> €</strong>
                                    <?php if ($producto->getTipo() == 'mochila'): ?>
                                        <br><small>(<?php echo __('precio_efectivo'); ?>:
                                            <?= number_format($producto->getPrecio() * 2, 2) ?> €)</small>
                                    <?php endif; ?>
                                </p>

                                <p><strong><?php echo __('tipo'); ?>:</strong> <?= __($producto->getTipo()) ?></p>

                                <!-- Sistema de valoracion en CARRUSEL -->
                                <div class="rating-simple" data-product-id="<?= $producto->getId() ?>">
                                    <div class="rating-title">Valoracion:</div>
                                    <div class="rating-stars-simple">
                                        <span class="star-simple" data-value="1">★</span>
                                        <span class="star-simple" data-value="2">★</span>
                                        <span class="star-simple" data-value="3">★</span>
                                        <span class="star-simple" data-value="4">★</span>
                                        <span class="star-simple" data-value="5">★</span>
                                    </div>
                                    <div class="rating-result">
                                        <span class="rating-average-simple">0.0</span>
                                        <span class="rating-count-simple">(0)</span>
                                    </div>
                                </div>

                                <?php if ($producto->getImagen()): ?>
                                    <img src="uploads/<?= htmlspecialchars($producto->getImagen()) ?>"
                                        alt="<?= htmlspecialchars($producto->getNombre()) ?>" class="img-fluid carousel-img">
                                <?php else: ?>
                                    <div class="carousel-img-placeholder">
                                        <span>𐙚</span>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex gap-10 mt-20">
                                    <!-- Boton Anadir al Carrito -->
                                    <form action="carrito/agregar.php" method="post" class="w-50">
                                        <input type="hidden" name="id" value="<?= $producto->getId() ?>">
                                        <?php if ($yaEnCarrito): ?>
                                            <a href="carrito/ver.php" class="w-100 text-center ya-en-carrito">
                                                𝜗𝜚 <?php echo __('ya_en_carrito'); ?>
                                            </a>
                                        <?php else: ?>
                                            <button type="submit" class="w-100 carrito-btn">𝜗𝜚 <?php echo __('anadir_carrito'); ?></button>
                                        <?php endif; ?>
                                    </form>

                                    <!-- Boton Anadir a Lista de Deseos -->
                                    <form action="deseos/agregar.php" method="post" class="w-50">
                                        <input type="hidden" name="id" value="<?= $producto->getId() ?>">
                                        <?php if ($yaEnDeseos): ?>
                                            <a href="deseos/ver.php" class="w-100 text-center ya-en-deseos">
                                                𝜗𝜚 <?php echo __('ya_en_deseos'); ?>
                                            </a>
                                        <?php else: ?>
                                            <button type="submit" class="w-100 deseo-btn">𝜗𝜚 <?php echo __('anadir_deseos'); ?></button>
                                        <?php endif; ?>
                                    </form>
                                </div>

                                <?php if ($producto->getTipo() == 'bolso'): ?>
                                    <div class="info-box mt-10">
                                        <strong>𝜗𝜚 <?php echo __('oferta_especial'); ?>!</strong><br>
                                        <?php echo __('precio_fijo_desc'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- TODOS LOS PRODUCTOS -->
        <h2 class="mt-30">Todos los Productos</h2>
        <div class="productos-container mt-20">
            <?php
            /* Iterar sobre la coleccion de productos y preparar variables de estado por producto */
            foreach ($productos as $producto):
                $yaEnCarrito = in_array($producto->getId(), $carritoIds);
                $yaEnDeseos = in_array($producto->getId(), $deseosIds);
                ?>
                <div class="producto" data-product-id="<?= $producto->getId() ?>">
                    <h3 class="d-flex justify-between align-center">
                        <?= htmlspecialchars($producto->getNombre()) ?>
                        <?php if ($producto->getTipo() == 'bolso'): ?>
                            <span class="badge badge-oferta"><?php echo __('oferta'); ?></span>
                        <?php elseif ($producto->getTipo() == 'mochila'): ?>
                            <span class="badge badge-premium">PREMIUM</span>
                        <?php endif; ?>
                    </h3>

                    <p class="precio">
                        <strong><?= number_format($producto->getPrecio(), 2) ?> €</strong>
                        <?php if ($producto->getTipo() == 'mochila'): ?>
                            <br><small>(<?php echo __('precio_efectivo'); ?>:
                                <?= number_format($producto->getPrecio() * 2, 2) ?> €)</small>
                        <?php endif; ?>
                    </p>

                    <p><strong><?php echo __('tipo'); ?>:</strong> <?= __($producto->getTipo()) ?></p>

                    <!-- Sistema de valoracion en PRODUCTOS NORMALES -->
                    <div class="rating-simple" data-product-id="<?= $producto->getId() ?>">
                        <div class="rating-title">Valoracion:</div>
                        <div class="rating-stars-simple">
                            <span class="star-simple" data-value="1">★</span>
                            <span class="star-simple" data-value="2">★</span>
                            <span class="star-simple" data-value="3">★</span>
                            <span class="star-simple" data-value="4">★</span>
                            <span class="star-simple" data-value="5">★</span>
                        </div>
                        <div class="rating-result">
                            <span class="rating-average-simple">0.0</span>
                            <span class="rating-count-simple">(0)</span>
                        </div>
                    </div>

                    <?php if ($producto->getImagen()): ?>
                        <img src="uploads/<?= htmlspecialchars($producto->getImagen()) ?>"
                            alt="<?= htmlspecialchars($producto->getNombre()) ?>" class="img-fluid">
                    <?php endif; ?>

                    <div class="d-flex gap-10 mt-20">
                        <!-- Boton Anadir al Carrito -->
                        <form action="carrito/agregar.php" method="post" class="w-50">
                            <input type="hidden" name="id" value="<?= $producto->getId() ?>">
                            <?php if ($yaEnCarrito): ?>
                                <a href="carrito/ver.php" class="w-100 text-center ya-en-carrito">
                                    𝜗𝜚 <?php echo __('ya_en_carrito'); ?>
                                </a>
                            <?php else: ?>
                                <button type="submit" class="w-100">𝜗𝜚 <?php echo __('anadir_carrito'); ?></button>
                            <?php endif; ?>
                        </form>

                        <!-- Boton Anadir a Lista de Deseos -->
                        <form action="deseos/agregar.php" method="post" class="w-50">
                            <input type="hidden" name="id" value="<?= $producto->getId() ?>">
                            <?php if ($yaEnDeseos): ?>
                                <a href="deseos/ver.php" class="w-100 text-center ya-en-deseos">
                                    𝜗𝜚 <?php echo __('ya_en_deseos'); ?>
                                </a>
                            <?php else: ?>
                                <button type="submit" class="w-100">𝜗𝜚 <?php echo __('anadir_deseos'); ?></button>
                            <?php endif; ?>
                        </form>
                    </div>

                    <?php if ($producto->getTipo() == 'bolso'): ?>
                        <div class="info-box mt-10">
                            <strong>𝜗𝜚 <?php echo __('oferta_especial'); ?>!</strong><br>
                            <?php echo __('precio_fijo_desc'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
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


    <!-- Scripts personalizados -->
    <script src="js/carousel.js"></script>
    <script src="js/ratings-all.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="js/madagascar-map.js"></script>
</body>

</html>