<?php
/* Iniciar sesion y restaurar variables de sesion */
session_start();

/* Cargar autoload para clases y dependencias */
require_once '../config/autoload.php';

/* Cargar conexion a base de datos */
require_once '../config/db.php';

/* Verificar que el usuario tenga rol admin y bloquear acceso en caso contrario */
if ($_SESSION['rol'] !== 'admin') die(__('error_acceso_denegado'));

/* Procesar cambio de estado de pedido cuando se recibe un formulario POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['nuevo_estado'])) {
    /* Recuperar datos enviados desde el formulario */
    $pedido_id = $_POST['pedido_id'];
    $nuevo_estado = $_POST['nuevo_estado'];
    
    /* Preparar consulta para actualizar el estado del pedido */
    $stmt = $conn->prepare(
        "UPDATE pedidos SET estado = ? WHERE id = ?"
    );
    $stmt->bind_param("si", $nuevo_estado, $pedido_id);
    /* Ejecutar la actualizacion en la base de datos */
    $stmt->execute();
    
    /* Redirigir de regreso a la lista de pedidos indicando exito */
    header("Location: pedidos.php?success=estado_cambiado");
    exit;
}

/* Obtener todos los pedidos con datos del usuario y conteo de productos por pedido */
$pedidosQuery = $conn->query(
    "SELECT p.*, u.usuario, COUNT(dp.id) as total_productos
     FROM pedidos p
     JOIN usuarios u ON p.usuario_id = u.id
     LEFT JOIN detalle_pedido dp ON p.id = dp.pedido_id
     GROUP BY p.id
     ORDER BY p.fecha DESC"
);

/* Obtener idioma actual y tema desde cookie con valor por defecto claro */
$idioma = getIdiomaActual();
$tema = $_COOKIE['tema'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">
<head>
    <title><?php echo __('gestion_pedidos'); ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/tema-<?php echo $tema; ?>.css">
</head>
<body class="tema-<?php echo $tema; ?>">
    <div class="container">
        <h1><?php echo __('gestion_pedidos'); ?></h1>
        
        <div class="nav-links">
            <a href="index.php">← <?php echo __('volver_admin'); ?></a>
        </div>
        
        <?php /* Mostrar mensaje de exito al cambiar estado */ if (isset($_GET['success']) && $_GET['success'] == 'estado_cambiado'): ?>
            <div class="success-message">
                <p>𝜗𝜚 <?php echo __('estado_actualizado'); ?></p>
            </div>
        <?php endif; ?>
        
        <?php /* Comprobar si no hay pedidos y mostrar mensaje informativo */ if ($pedidosQuery->num_rows === 0): ?>
            <div class="info-box text-center">
                <h3><?php echo __('no_hay_pedidos'); ?></h3>
            </div>
        <?php else: ?>
            <table class="mt-20">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><?php echo __('fecha'); ?></th>
                        <th><?php echo __('usuario'); ?></th>
                        <th><?php echo __('total'); ?></th>
                        <th><?php echo __('productos'); ?></th>
                        <th><?php echo __('estado'); ?></th>
                        <th><?php echo __('acciones'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php /* Iterar sobre pedidos y renderizar cada fila de la tabla */ while ($pedido = $pedidosQuery->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $pedido['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></td>
                        <td><?= htmlspecialchars($pedido['usuario']) ?></td>
                        <td><?= number_format($pedido['total'], 2) ?> €</td>
                        <td><?= $pedido['total_productos'] ?></td>
                        <td>
                            <span class="badge 
                                <?= $pedido['estado'] == 'completado' ? 'badge-success' : '' ?>
                                <?= $pedido['estado'] == 'procesado' ? 'badge-info' : '' ?>
                                <?= $pedido['estado'] == 'pendiente' ? 'badge-warning' : '' ?>
                                <?= $pedido['estado'] == 'cancelado' ? 'badge-error' : '' ?>">
                                <?= ucfirst($pedido['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-10">
                                <a href="detalle_pedido.php?id=<?= $pedido['id'] ?>" class="text-small">࣪˖ <?php echo __('ver'); ?></a>
                                
                                <?php if ($pedido['estado'] == 'pendiente'): ?>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                    <input type="hidden" name="nuevo_estado" value="procesado">
                                    <button type="submit" class="text-small" onclick="return confirm('<?php echo __('confirmar_procesar'); ?>')">
                                        𝜚𝜗 <?php echo __('procesar'); ?>
                                    </button>
                                </form>
                                
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                    <input type="hidden" name="nuevo_estado" value="cancelado">
                                    <button type="submit" class="text-small" onclick="return confirm('<?php echo __('confirmar_cancelar'); ?>')">
                                        ࣪𐙚 <?php echo __('cancelar'); ?>
                                    </button>
                                </form>
                                <?php elseif ($pedido['estado'] == 'procesado'): ?>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                    <input type="hidden" name="nuevo_estado" value="completado">
                                    <button type="submit" class="text-small" onclick="return confirm('<?php echo __('confirmar_completar'); ?>')">
                                        𐙚𝜗 <?php echo __('completar'); ?>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
