<?php
/* Inicio manejo de sesion y carga de dependencias */
/* iniciar session para mantener datos de usuario */
session_start();
/* cargar autoload y conexion a base de datos */
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Verifica que el usuario tenga rol admin y termina ejecucion si no esta autorizado */
if ($_SESSION['rol'] !== 'admin')
    die(__('error_acceso_denegado'));

/* Procesar cambio de estado recibido por POST */
/* Cuando se recibe un POST con pedido_id y nuevo_estado se actualiza el registro correspondiente */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['nuevo_estado'])) {
    $pedido_id = $_POST['pedido_id'];
    $nuevo_estado = $_POST['nuevo_estado'];

    /* Preparacion de la sentencia para actualizar estado en tabla pedidos */
    $stmt = $conn->prepare(
        "UPDATE pedidos SET estado = ? WHERE id = ?"
    );
    $stmt->bind_param("si", $nuevo_estado, $pedido_id);
    $stmt->execute();

    /* Redirigir de nuevo al detalle del pedido con indicador de exito */
    header("Location: detalle_pedido.php?id=" . $pedido_id . "&success=estado_cambiado");
    exit;
}

/* Obtiene id del pedido desde la query string o 0 si no viene */
$pedido_id = $_GET['id'] ?? 0;

/* Consulta datos del pedido y nombre de usuario asociado */
/* Se usa una consulta preparada para evitar inyeccion en la seleccion del pedido */
$stmt = $conn->prepare(
    "SELECT p.*, u.usuario 
     FROM pedidos p 
     JOIN usuarios u ON p.usuario_id = u.id 
     WHERE p.id = ?"
);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

/* Si no se encontro el pedido se redirige a la lista de pedidos con error */
if (!$pedido) {
    header("Location: pedidos.php?error=pedido_no_encontrado");
    exit;
}

/* Consulta los detalles del pedido junto con datos de producto */
/* Esta consulta se realiza con interpolacion de la variable id porque ya se uso validacion previa */
$detallesQuery = $conn->query(
    "SELECT dp.*, pr.nombre, pr.tipo, pr.imagen
     FROM detalle_pedido dp
     JOIN productos pr ON dp.producto_id = pr.id
     WHERE dp.pedido_id = $pedido_id"
);

/* Obtiene configuracion de idioma y tema de la aplicacion */
/* getIdiomaActual es una funcion de la aplicacion que devuelve el idioma en uso */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';

/* Inicio renderizado de plantilla HTML a continuacion no agregar comentarios dentro del HTML */
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">

<head>
    <title><?php echo __('detalle_pedido_admin'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>

<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <h1><?php echo __('detalle_pedido_admin'); ?> #<?= $pedido['id'] ?></h1>

        <div class="nav-links">
            <a href="pedidos.php">← <?php echo __('volver_pedidos'); ?></a>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 'estado_cambiado'): ?>
            <div class="success-message">
                <p>⨀⨂ <?php echo __('estado_actualizado'); ?></p>
            </div>
        <?php endif; ?>

        <div class="info-box mt-20">
            <h3><?php echo __('informacion_pedido'); ?></h3>
            <p><strong>ID:</strong> #<?= $pedido['id'] ?></p>
            <p><strong><?php echo __('fecha'); ?>:</strong> <?= date('d/m/Y H:i:s', strtotime($pedido['fecha'])) ?></p>
            <p><strong><?php echo __('usuario'); ?>:</strong> <?= htmlspecialchars($pedido['usuario']) ?> (ID:
                <?= $pedido['usuario_id'] ?>)
            </p>
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
                <tr class="precio">
                    <td colspan="4" style="text-align: right;"><strong><?php echo __('total'); ?>:</strong></td>
                    <td><strong><?= number_format($pedido['total'], 2) ?> €</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex gap-10 mt-30">
            <?php if ($pedido['estado'] == 'pendiente'): ?>
                <form method="post" class="w-100">
                    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                    <input type="hidden" name="nuevo_estado" value="procesado">
                    <button type="submit" class="w-100" onclick="return confirm('<?php echo __('confirmar_procesar'); ?>')">
                        ⨀⨂ <?php echo __('marcar_procesado'); ?>
                    </button>
                </form>

                <form method="post" class="w-100">
                    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                    <input type="hidden" name="nuevo_estado" value="cancelado">
                    <button type="submit" class="w-100" onclick="return confirm('<?php echo __('confirmar_cancelar'); ?>')">
                        ⨳⨴ <?php echo __('cancelar_pedido'); ?>
                    </button>
                </form>
            <?php elseif ($pedido['estado'] == 'procesado'): ?>
                <form method="post" class="w-100">
                    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                    <input type="hidden" name="nuevo_estado" value="completado">
                    <button type="submit" class="w-100"
                        onclick="return confirm('<?php echo __('confirmar_completar'); ?>')">
                        ✺⟆ <?php echo __('marcar_completado'); ?>
                    </button>
                </form>

                <form method="post" class="w-100">
                    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                    <input type="hidden" name="nuevo_estado" value="pendiente">
                    <button type="submit" class="w-100" onclick="return confirm('¿Volver a estado pendiente?')">
                        ⟂⟃ <?php echo __('volver_pendiente'); ?>
                    </button>
                </form>
            <?php elseif ($pedido['estado'] == 'completado'): ?>
                <div class="info-box w-100 text-center">
                    <p>⨀⨂ <?php echo __('pedido_completado'); ?></p>
                    <p><?php echo __('no_acciones_disponibles'); ?></p>
                </div>
            <?php elseif ($pedido['estado'] == 'cancelado'): ?>
                <div class="info-box w-100 text-center">
                    <p>⨳⨴ <?php echo __('pedido_cancelado'); ?></p>
                    <p><?php echo __('no_acciones_disponibles'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>