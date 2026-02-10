<?php
/* Archivo detalle_pedido.php
   Muestra el detalle de un pedido para el usuario logueado
   No se modifica la logica original solo se agregan comentarios
*/

/* Iniciar sesion y cargar dependencias */
session_start();
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Verificar sesion activa y redirigir a login si no esta autenticado */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Obtener identificador de usuario desde la sesion
   y obtener id de pedido desde la consulta GET con valor por defecto 0
*/
$usuario_id = $_SESSION['id'];
$pedido_id = $_GET['id'] ?? 0;

/* Preparar y ejecutar consulta segura para verificar que el pedido
   pertenece al usuario autenticado
   Uso de prepared statement para evitar inyeccion SQL
*/
$stmt = $conn->prepare(
    "SELECT p.* FROM pedidos p WHERE p.id = ? AND p.usuario_id = ?"
);
$stmt->bind_param("ii", $pedido_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

/* Si no se encuentra el pedido o no pertenece al usuario
   redirigir a la lista de pedidos con un error
*/
if (!$pedido) {
    header("Location: mis_pedidos.php?error=pedido_no_encontrado");
    exit;
}

/* Obtener los detalles del pedido incluyendo informacion del producto
   Se usa una consulta con JOIN para traer nombre, tipo e imagen del producto
   Nota se mantiene la consulta tal cual que devuelve un objeto mysqli_result
*/
$detallesQuery = $conn->query(
    "SELECT dp.*, pr.nombre, pr.tipo, pr.imagen
     FROM detalle_pedido dp
     JOIN productos pr ON dp.producto_id = pr.id
     WHERE dp.pedido_id = $pedido_id"
);

/* Obtener configuracion de idioma y tema
   getIdiomaActual es una funcion definida en las dependencias incluidas
   El tema se obtiene desde cookie con valor por defecto claro
*/
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('detalle_pedido'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <h1><?php echo __('detalle_pedido'); ?> #<?= $pedido['id'] ?></h1>

        <div class="nav-links">
            <a href="mis_pedidos.php">⟞⟟ <?php echo __('volver_pedidos'); ?></a>
        </div>

        <div class="info-box mt-20">
            <h3><?php echo __('informacion_pedido'); ?></h3>
            <p><strong><?php echo __('fecha'); ?>:</strong> <?= date('d/m/Y H:i:s', strtotime($pedido['fecha'])) ?></p>
            <p><strong><?php echo __('estado'); ?>:</strong>
                <span class="badge 
                    <?= $pedido['estado'] == 'completado' ? 'badge-success' : '' ?>
                    <?= $pedido['estado'] == 'procesado' ? 'badge-info' : '' ?>
                    <?= $pedido['estado'] == 'pendiente' ? 'badge-warning' : '' ?>
                    <?= $pedido['estado'] == 'cancelado' ? 'badge-error' : '' ?>">
                    <?= ucfirst($pedido['estado']) ?>
                </span>
            </p>
            <p class="precio mt-10"><strong><?php echo __('total'); ?>:</strong>
                <?= number_format($pedido['total'], 2) ?> €</p>
        </div>

        <h3 class="mt-30"><?php echo __('productos_pedido'); ?></h3>

        <div class="productos-container mt-20">
            <?php while ($detalle = $detallesQuery->fetch_assoc()): ?>
                <div class="producto">
                    <?php if ($detalle['imagen']): ?>
                        <img src="../uploads/<?= $detalle['imagen'] ?>" width="100" class="img-thumbnail">
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($detalle['nombre']) ?></h3>
                    <p><strong><?php echo __('tipo'); ?>:</strong> <?= __($detalle['tipo']) ?></p>
                    <p><strong><?php echo __('cantidad'); ?>:</strong> <?= $detalle['cantidad'] ?></p>
                    <p><strong><?php echo __('precio_unitario'); ?>:</strong>
                        <?= number_format($detalle['precio_unitario'], 2) ?> €</p>
                    <p><strong><?php echo __('subtotal'); ?>:</strong> <?= number_format($detalle['subtotal'], 2) ?> €</p>

                    <?php if ($detalle['tipo'] == 'bolso'): ?>
                        <div class="info-box mt-10">
                            <strong>𐌗𐌘 <?php echo __('oferta_especial'); ?>!</strong><br>
                            <?php echo __('precio_fijo_desc'); ?>
                        </div>
                    <?php elseif ($detalle['tipo'] == 'mochila'): ?>
                        <div class="info-box mt-10">
                            <strong>⟁⟟ <?php echo __('producto_premium'); ?></strong><br>
                            <?php echo __('precio_doble_desc'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if ($pedido['estado'] == 'pendiente'): ?>
            <div class="info-box mt-20">
                <p><strong>◖◗ <?php echo __('estado_pendiente'); ?></strong></p>
                <p><?php echo __('espera_confirmacion'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>