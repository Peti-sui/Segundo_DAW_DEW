<?php
/* Inicia la sesion del usuario para poder usar variables de sesion */
session_start();
/* Carga el autoload y la configuracion de la base de datos */
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Obtiene el id del usuario desde la sesion */
$id = $_SESSION['id'];

/* Ejecuta la consulta para obtener los items del carrito junto con datos del producto */
$res = $conn->query(
    "SELECT c.id AS carrito_id, p.id AS producto_id, p.nombre, p.precio, p.tipo, p.imagen, c.cantidad
     FROM carrito c
     JOIN productos p ON c.producto_id = p.id
     WHERE c.usuario_id = $id"
);

/* Inicializa el total y el array de items */
$total = 0;
$items = [];

/* Recorre los resultados y rellena el array de items */
while ($r = $res->fetch_assoc()) {
    $items[] = $r;
}

/* Obtiene el idioma actual para las traducciones y el tema desde la cookie */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';

/* Maneja los posibles mensajes de error y exito recibidos via GET */
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('carrito'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <h1><?php echo __('carrito'); ?></h1>

        <div class="nav-links">
            <a href="../index.php">𝜗𝜚 <?php echo __('volver_tienda'); ?></a>
        </div>

        <?php if ($error === 'ya_en_carrito'): ?>
            <div class="error-message">
                <p>𐙚˖ <?php echo __('producto_ya_en_carrito'); ?></p>
                <p><?php echo __('ir_carrito_modificar'); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($success === 'añadido'): ?>
            <div class="success-message">
                <p>࣪˖ <?php echo __('producto_añadido'); ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <div class="info-box text-center">
                <h3><?php echo __('carrito_vacio'); ?></h3>
                <a href="../index.php" class="mt-20">𑁍𝜚 <?php echo __('seguir_comprando'); ?></a>
            </div>
        <?php else: ?>
            <div class="productos-container">
                <?php foreach ($items as $item):
                    $producto = Producto::crearDesdeArray($item);
                    $precioCalculado = $producto->calcularPrecio($item['cantidad']);
                    $total += $precioCalculado;
                    ?>
                    <div class="producto">
                        <?php if ($item['imagen']): ?>
                            <img src="../uploads/<?= $item['imagen'] ?>" width="100" class="img-thumbnail">
                        <?php endif; ?>

                        <h3><?= htmlspecialchars($item['nombre']) ?></h3>
                        <p><strong><?php echo __('tipo'); ?>:</strong> <?= __($item['tipo']) ?></p>
                        <p><strong><?php echo __('precio_unitario'); ?>:</strong> <?= number_format($item['precio'], 2) ?> €</p>

                        <div class="carrito-acciones mt-20">
                            <form action="actualizar.php" method="post">
                                <input type="hidden" name="carrito_id" value="<?= $item['carrito_id'] ?>">
                                <button type="submit" name="accion" value="menos">−</button>
                                <span class="carrito-cantidad"><?= $item['cantidad'] ?></span>
                                <button type="submit" name="accion" value="mas">+</button>
                            </form>
                        </div>

                        <p class="mt-20"><strong><?php echo __('subtotal'); ?>:</strong> <span
                                class="precio"><?= number_format($precioCalculado, 2) ?> €</span></p>

                        <?php if ($item['tipo'] == 'bolso'): ?>
                            <div class="info-box mt-10">
                                <strong>ֈ𝜗 <?php echo __('oferta_especial'); ?>!</strong><br>
                                <?php echo __('precio_fijo_desc'); ?>
                            </div>
                        <?php elseif ($item['tipo'] == 'mochila'): ?>
                            <div class="info-box mt-10">
                                <strong>𐊢𝜚 <?php echo __('producto_premium'); ?></strong><br>
                                <?php echo __('precio_doble_desc'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="info-box mt-30">
                <h3><?php echo __('resumen_carrito'); ?></h3>
                <p class="precio"><b><?php echo __('total'); ?>: <?= number_format($total, 2) ?> €</b></p>
            </div>

            <div class="d-flex gap-10 mt-20">
                <form action="vaciar.php" method="post" class="w-100">
                    <button class="w-100">꧁𐙚 <?php echo __('vaciar_carrito'); ?></button>
                </form>
                <form action="procesar_pago.php" method="post" class="w-100">
                    <button type="submit" class="w-100">༒𝜚 <?php echo __('proceder_pago'); ?></button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($error === 'ya_en_carrito'): ?>
        <script>
            setTimeout(function () {
                alert('<?php echo __('producto_ya_en_carrito_alerta'); ?>');
            }, 500);
        </script>
    <?php endif; ?>
</body>

</html>