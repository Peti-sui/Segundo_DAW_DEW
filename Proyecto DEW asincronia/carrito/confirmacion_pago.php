<?php
/* Inicio del script manejo de confirmacion de pago y visualizacion de detalles del pedido */
/* Iniciar sesion para manejar datos de usuario y validacion de acceso */
session_start();
/* Cargar autoload y conexion a base de datos necesarios para consultas y clases */
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Verificar que el usuario esta autenticado si no redirigir al login */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Obtener id de usuario desde la sesion y id del pedido desde GET */
$usuario_id = $_SESSION['id'];
$pedido_id = $_GET['id'] ?? 0;

/* Preparar consulta para verificar que el pedido pertenece al usuario */
/* Se usa una consulta segura con prepared statement para evitar inyeccion de parametros */
/* Bind de parametros y ejecucion de la consulta */
$stmt = $conn->prepare(
    "SELECT p.*, u.usuario 
     FROM pedidos p 
     JOIN usuarios u ON p.usuario_id = u.id 
     WHERE p.id = ? AND p.usuario_id = ?"
);
$stmt->bind_param("ii", $pedido_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
/* Obtener datos del pedido como array asociativo */
$pedido = $result->fetch_assoc();

/* Comprobar que el pedido existe y pertenece al usuario */
/* Si no existe el pedido para este usuario redirigir a la lista con error */
if (!$pedido) {
    header("Location: ver.php?error=pedido_no_encontrado");
    exit;
}

/* Obtener detalles del pedido con join a productos para mostrar nombre tipo e imagen */
/* Nota no se modifica la logica original de la consulta aunque use query directa */
$detallesQuery = $conn->query(
    "SELECT dp.*, pr.nombre, pr.tipo, pr.imagen
     FROM detalle_pedido dp
     JOIN productos pr ON dp.producto_id = pr.id
     WHERE dp.pedido_id = $pedido_id"
);

/* Obtener preferencias de idioma y tema del usuario para renderizado */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('confirmacion_pago'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <div class="preferencias-container">
            <h1 class="text-center">⟁⟒ <?php echo __('pago_exitoso'); ?></h1>

            <div class="info-box mt-20">
                <h3><?php echo __('resumen_pedido'); ?> #<?= $pedido['id'] ?></h3>
                <p><strong><?php echo __('fecha'); ?>:</strong> <?= date('d/m/Y H:i:s', strtotime($pedido['fecha'])) ?>
                </p>
                <p><strong><?php echo __('usuario'); ?>:</strong> <?= htmlspecialchars($pedido['usuario']) ?></p>
                <p><strong><?php echo __('estado'); ?>:</strong> <?= ucfirst($pedido['estado']) ?></p>
                <p class="precio mt-10"><strong><?php echo __('total'); ?>:</strong>
                    <?= number_format($pedido['total'], 2) ?> €</p>
            </div>

            <h3 class="mt-30"><?php echo __('detalles_pedido'); ?></h3>

            <table class="mt-20">
                <thead>
                    <tr>
                        <th><?php echo __('producto'); ?></th>
                        <th><?php echo __('tipo'); ?></th>
                        <th><?php echo __('cantidad'); ?></th>
                        <th><?php echo __('precio_unitario'); ?></th>
                        <th><?php echo __('subtotal'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($detalle = $detallesQuery->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($detalle['nombre']) ?></td>
                            <td><?= __($detalle['tipo']) ?></td>
                            <td><?= $detalle['cantidad'] ?></td>
                            <td><?= number_format($detalle['precio_unitario'], 2) ?> €</td>
                            <td><?= number_format($detalle['subtotal'], 2) ?> €</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="d-flex gap-10 mt-30">
                <a href="../index.php" class="w-100 text-center">⟟⟠ <?php echo __('seguir_comprando'); ?></a>
                <a href="mis_pedidos.php" class="w-100 text-center">⟡⟢ <?php echo __('ver_mis_pedidos'); ?></a>
            </div>

            <div class="text-center mt-20">
                <a href="ver.php" class="text-small">⤶ <?php echo __('volver_carrito'); ?></a>
            </div>
        </div>
    </div>
</body>

</html>